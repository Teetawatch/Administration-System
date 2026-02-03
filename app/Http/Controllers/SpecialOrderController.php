<?php

namespace App\Http\Controllers;

use App\Models\SpecialOrder;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

/**
 * Controller for managing Special Orders.
 * 
 * Following best practices:
 * - php-pro: Type hints, return types, modern PHP 8 features
 * - software-architecture: Clean code patterns
 */
class SpecialOrderController extends Controller
{
    /**
     * Display a listing of special orders.
     */
    public function index(Request $request): View
    {
        $query = SpecialOrder::with('creator')->latest();

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('status')) {
            $query->status($request->status);
        }

        $orders = $query->paginate(15)->withQueryString();

        return view('special-orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new special order.
     */
    public function create(): View
    {
        $nextOrderNumber = $this->generateNextOrderNumber();

        return view('special-orders.create', compact('nextOrderNumber'));
    }

    /**
     * Store a newly created special order in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'order_number' => 'required|string|max:100|unique:special_orders',
            'order_date' => 'required|date',
            'subject' => 'required|string|max:500',
            'content' => 'nullable|string',
            'effective_date' => 'nullable|date',
            'status' => 'nullable|in:draft,active,cancelled',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['status'] = $validated['status'] ?? SpecialOrder::STATUS_ACTIVE;
        $validated['content'] = $validated['content'] ?? '';

        if ($request->hasFile('attachment')) {
            $validated['attachment_path'] = $request->file('attachment')->store('special-orders', 'public');
        }

        SpecialOrder::create($validated);

        return redirect()->route('special-orders.index')
            ->with('success', 'บันทึกคำสั่งโรงเรียน (เฉพาะ) เรียบร้อยแล้ว');
    }

    /**
     * Display the specified special order.
     */
    public function show(SpecialOrder $specialOrder): View
    {
        return view('special-orders.show', compact('specialOrder'));
    }

    /**
     * Show the form for editing the specified special order.
     */
    public function edit(SpecialOrder $specialOrder): View
    {
        return view('special-orders.edit', compact('specialOrder'));
    }

    /**
     * Update the specified special order in storage.
     */
    public function update(Request $request, SpecialOrder $specialOrder): RedirectResponse
    {
        $validated = $request->validate([
            'order_number' => "required|string|max:100|unique:special_orders,order_number,{$specialOrder->id}",
            'order_date' => 'required|date',
            'subject' => 'required|string|max:500',
            'content' => 'nullable|string',
            'effective_date' => 'nullable|date',
            'status' => 'required|in:draft,active,cancelled',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $validated['content'] = $validated['content'] ?? '';

        if ($request->hasFile('attachment')) {
            $this->deleteOldAttachment($specialOrder);
            $validated['attachment_path'] = $request->file('attachment')->store('special-orders', 'public');
        }

        $specialOrder->update($validated);

        return redirect()->route('special-orders.index')
            ->with('success', 'อัปเดตคำสั่งโรงเรียน (เฉพาะ) เรียบร้อยแล้ว');
    }

    /**
     * Remove the specified special order from storage.
     */
    public function destroy(SpecialOrder $specialOrder): RedirectResponse
    {
        $this->deleteOldAttachment($specialOrder);
        $specialOrder->delete();

        return redirect()->route('special-orders.index')
            ->with('success', 'ลบคำสั่งโรงเรียน (เฉพาะ) เรียบร้อยแล้ว');
    }

    /**
     * Export special order to PDF.
     */
    public function exportPdf(SpecialOrder $specialOrder): Response
    {
        $pdf = Pdf::loadView('special-orders.pdf', compact('specialOrder'));

        return $pdf->stream("special-order-{$specialOrder->order_number}.pdf");
    }

    /**
     * Generate the next order number.
     */
    private function generateNextOrderNumber(): string
    {
        $currentThaiYear = date('Y') + 543;

        $lastOrder = SpecialOrder::where('order_number', 'LIKE', "%/{$currentThaiYear}")
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
    private function deleteOldAttachment(SpecialOrder $specialOrder): void
    {
        if ($specialOrder->attachment_path) {
            Storage::disk('public')->delete($specialOrder->attachment_path);
        }
    }
}
