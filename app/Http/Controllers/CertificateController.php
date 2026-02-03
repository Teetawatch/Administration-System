<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Controller for managing Certificates.
 * 
 * Following best practices:
 * - php-pro: Type hints, return types, modern PHP 8 features
 * - software-architecture: Clean code patterns
 */
class CertificateController extends Controller
{
    /**
     * Display a listing of certificates.
     */
    public function index(Request $request): View
    {
        $query = Certificate::with('creator')->latest();

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('status')) {
            $query->status($request->status);
        }

        $certificates = $query->paginate(15)->withQueryString();

        return view('certificates.index', compact('certificates'));
    }

    /**
     * Show the form for creating a new certificate.
     */
    public function create(): View
    {
        $nextCertificateNumber = $this->generateNextCertificateNumber();

        return view('certificates.create', compact('nextCertificateNumber'));
    }

    /**
     * Store a newly created certificate in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'certificate_number' => 'required|string|max:100|unique:certificates',
            'issue_date' => 'required|date',
            'personnel_name' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'purpose' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'status' => 'nullable|in:draft,issued,cancelled',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['status'] = $validated['status'] ?? Certificate::STATUS_ISSUED;

        Certificate::create($validated);

        return redirect()->route('certificates.index')
            ->with('success', 'บันทึกหนังสือรับรองเรียบร้อยแล้ว');
    }

    /**
     * Display the specified certificate.
     */
    public function show(Certificate $certificate): View
    {
        return view('certificates.show', compact('certificate'));
    }

    /**
     * Show the form for editing the specified certificate.
     */
    public function edit(Certificate $certificate): View
    {
        return view('certificates.edit', compact('certificate'));
    }

    /**
     * Update the specified certificate in storage.
     */
    public function update(Request $request, Certificate $certificate): RedirectResponse
    {
        $validated = $request->validate([
            'certificate_number' => "required|string|max:100|unique:certificates,certificate_number,{$certificate->id}",
            'issue_date' => 'required|date',
            'personnel_name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'purpose' => 'required|string|max:500',
            'content' => 'nullable|string',
            'status' => 'required|in:draft,issued,cancelled',
        ]);

        $certificate->update($validated);

        return redirect()->route('certificates.index')
            ->with('success', 'อัปเดตหนังสือรับรองเรียบร้อยแล้ว');
    }

    /**
     * Remove the specified certificate from storage.
     */
    public function destroy(Certificate $certificate): RedirectResponse
    {
        $certificate->delete();

        return redirect()->route('certificates.index')
            ->with('success', 'ลบหนังสือรับรองเรียบร้อยแล้ว');
    }

    /**
     * Export certificate to PDF.
     */
    public function exportPdf(Certificate $certificate): Response
    {
        $pdf = Pdf::loadView('certificates.pdf', compact('certificate'));

        return $pdf->stream("certificate-{$certificate->certificate_number}.pdf");
    }

    /**
     * Generate the next certificate number.
     */
    private function generateNextCertificateNumber(): string
    {
        $currentThaiYear = date('Y') + 543;

        $lastCert = Certificate::where('certificate_number', 'LIKE', "%/{$currentThaiYear}")
            ->latest('id')
            ->first();

        $runningNumber = 1;

        if ($lastCert && preg_match('/^(\d+)\//', $lastCert->certificate_number, $matches)) {
            $runningNumber = intval($matches[1]) + 1;
        }

        return "{$runningNumber}/{$currentThaiYear}";
    }
}
