<?php

namespace App\Http\Controllers;

use App\Models\SchoolOrder;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

/**
 * Controller for managing School Orders.
 * 
 * Following best practices:
 * - php-pro: Type hints, return types, modern PHP 8 features
 * - software-architecture: Clean code patterns
 */
class SchoolOrderController extends Controller
{
    /**
     * Display a listing of school orders.
     */
    public function index(Request $request): View
    {
        $query = SchoolOrder::with('creator')->latest();

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('status')) {
            $query->status($request->status);
        }

        $orders = $query->paginate(15)->withQueryString();

        return view('school-orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new school order.
     */
    public function create(): View
    {
        $nextOrderNumber = $this->generateNextOrderNumber();

        return view('school-orders.create', compact('nextOrderNumber'));
    }

    /**
     * Store a newly created school order in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'order_number' => 'required|string|max:100|unique:school_orders',
            'order_date' => 'required|date',
            'subject' => 'required|string|max:500',
            'content' => 'nullable|string',
            'effective_date' => 'nullable|date',
            'status' => 'nullable|in:draft,active,cancelled',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['status'] = $validated['status'] ?? SchoolOrder::STATUS_ACTIVE;
        $validated['content'] = $validated['content'] ?? '';

        if ($request->hasFile('attachment')) {
            $validated['attachment_path'] = $request->file('attachment')->store('school-orders', 'public');
        }

        SchoolOrder::create($validated);

        return redirect()->route('school-orders.index')
            ->with('success', 'บันทึกคำสั่งโรงเรียนเรียบร้อยแล้ว');
    }

    /**
     * Display the specified school order.
     */
    public function show(SchoolOrder $schoolOrder): View
    {
        return view('school-orders.show', compact('schoolOrder'));
    }

    /**
     * Show the form for editing the specified school order.
     */
    public function edit(SchoolOrder $schoolOrder): View
    {
        return view('school-orders.edit', compact('schoolOrder'));
    }

    /**
     * Update the specified school order in storage.
     */
    public function update(Request $request, SchoolOrder $schoolOrder): RedirectResponse
    {
        $validated = $request->validate([
            'order_number' => "required|string|max:100|unique:school_orders,order_number,{$schoolOrder->id}",
            'order_date' => 'required|date',
            'subject' => 'required|string|max:500',
            'content' => 'nullable|string',
            'effective_date' => 'nullable|date',
            'status' => 'required|in:draft,active,cancelled',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $validated['content'] = $validated['content'] ?? '';

        if ($request->hasFile('attachment')) {
            $this->deleteOldAttachment($schoolOrder);
            $validated['attachment_path'] = $request->file('attachment')->store('school-orders', 'public');
        }

        $schoolOrder->update($validated);

        return redirect()->route('school-orders.index')
            ->with('success', 'อัปเดตคำสั่งโรงเรียนเรียบร้อยแล้ว');
    }

    /**
     * Remove the specified school order from storage.
     */
    public function destroy(SchoolOrder $schoolOrder): RedirectResponse
    {
        $this->deleteOldAttachment($schoolOrder);
        $schoolOrder->delete();

        return redirect()->route('school-orders.index')
            ->with('success', 'ลบคำสั่งโรงเรียนเรียบร้อยแล้ว');
    }

    /**
     * Export school order to PDF.
     */
    public function exportPdf(SchoolOrder $schoolOrder): Response
    {
        $pdf = Pdf::loadView('school-orders.pdf', compact('schoolOrder'));

        return $pdf->stream("school-order-{$schoolOrder->order_number}.pdf");
    }

    /**
     * Generate the next order number.
     */
    private function generateNextOrderNumber(): string
    {
        $currentThaiYear = date('Y') + 543;

        $lastOrder = SchoolOrder::where('order_number', 'LIKE', "%/{$currentThaiYear}")
            ->latest('id')
            ->first();

        $runningNumber = 1;

        if ($lastOrder && preg_match('/^(\d+)\//', $lastOrder->order_number, $matches)) {
            $runningNumber = intval($matches[1]) + 1;
        }

        return "{$runningNumber}/{$currentThaiYear}";
    }

    /**
     * Delete old attachment if exists.
     */
    private function deleteOldAttachment(SchoolOrder $schoolOrder): void
    {
        if ($schoolOrder->attachment_path) {
            Storage::disk('public')->delete($schoolOrder->attachment_path);
        }
    }
}
