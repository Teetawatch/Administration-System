<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CertificateController extends Controller
{
    public function index(Request $request)
    {
        $query = Certificate::with('creator')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('certificate_number', 'like', "%{$search}%")
                  ->orWhere('personnel_name', 'like', "%{$search}%")
                  ->orWhere('purpose', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $certificates = $query->paginate(15)->withQueryString();

        return view('certificates.index', compact('certificates'));
    }

    public function create()
    {
        // Auto-generate certificate number (Format: Running Number / Thai Year e.g. 1/2569)
        $currentThaiYear = date('Y') + 543;
        $lastCert = Certificate::where('certificate_number', 'LIKE', "%/{$currentThaiYear}")
            ->latest('id')
            ->first();

        $runningNumber = 1;

        if ($lastCert) {
            // Extract the number part before the slash
            if (preg_match('/^(\d+)\//', $lastCert->certificate_number, $matches)) {
                $runningNumber = intval($matches[1]) + 1;
            }
        }

        $nextCertificateNumber = "{$runningNumber}/{$currentThaiYear}";

        return view('certificates.create', compact('nextCertificateNumber'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'certificate_number' => 'required|string|max:100|unique:certificates',
            'issue_date' => 'required|date',
            // Removed required fields as per request
            'personnel_name' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'purpose' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'status' => 'nullable|in:draft,issued,cancelled',
        ]);

        $validated['created_by'] = Auth::id();
        // Default status if not provided
        $validated['status'] = $validated['status'] ?? 'issued';

        Certificate::create($validated);

        return redirect()->route('certificates.index')
            ->with('success', 'บันทึกหนังสือรับรองเรียบร้อยแล้ว');
    }

    public function show(Certificate $certificate)
    {
        return view('certificates.show', compact('certificate'));
    }

    public function edit(Certificate $certificate)
    {
        return view('certificates.edit', compact('certificate'));
    }

    public function update(Request $request, Certificate $certificate)
    {
        $validated = $request->validate([
            'certificate_number' => 'required|string|max:100|unique:certificates,certificate_number,' . $certificate->id,
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

    public function destroy(Certificate $certificate)
    {
        $certificate->delete();

        return redirect()->route('certificates.index')
            ->with('success', 'ลบหนังสือรับรองเรียบร้อยแล้ว');
    }

    public function exportPdf(Certificate $certificate)
    {
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('certificates.pdf', compact('certificate'));
        return $pdf->stream('certificate-' . $certificate->certificate_number . '.pdf');
    }
}
