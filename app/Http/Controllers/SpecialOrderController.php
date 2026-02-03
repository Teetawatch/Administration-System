<?php

namespace App\Http\Controllers;

use App\Models\SpecialOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SpecialOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = SpecialOrder::with('creator')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }



        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(15)->withQueryString();

        return view('special-orders.index', compact('orders'));
    }

    public function create()
    {
        // Auto-generate order number (Format: Running/ThaiYear e.g. 1/2569)
        $currentThaiYear = date('Y') + 543;
        $lastOrder = SpecialOrder::where('order_number', 'LIKE', "%/{$currentThaiYear}")
            ->latest('id')
            ->first();

        $runningNumber = 1;

        if ($lastOrder) {
            // Extract the number part before the slash
            if (preg_match('/^(\d+)\//', $lastOrder->order_number, $matches)) {
                $runningNumber = intval($matches[1]) + 1;
            }
        }

        $nextOrderNumber = "{$runningNumber}/{$currentThaiYear}";

        return view('special-orders.create', compact('nextOrderNumber'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_number' => 'required|string|max:100|unique:special_orders',
            'order_date' => 'required|date',
            'subject' => 'required|string|max:500',
            'content' => 'nullable|string',

            'effective_date' => 'nullable|date',
            'status' => 'nullable|in:draft,active,cancelled', // Made nullable
            'attachment' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['status'] = $validated['status'] ?? 'active'; // Default to active if not provided

        if ($request->hasFile('attachment')) {
            $validated['attachment_path'] = $request->file('attachment')->store('special-orders', 'public');
        }

        $validated['content'] = $validated['content'] ?? '';

        SpecialOrder::create($validated);

        return redirect()->route('special-orders.index')
            ->with('success', 'บันทึกคำสั่งโรงเรียน (เฉพาะ) เรียบร้อยแล้ว');
    }

    public function show(SpecialOrder $specialOrder)
    {
        return view('special-orders.show', compact('specialOrder'));
    }

    public function edit(SpecialOrder $specialOrder)
    {
        return view('special-orders.edit', compact('specialOrder'));
    }

    public function update(Request $request, SpecialOrder $specialOrder)
    {
        $validated = $request->validate([
            'order_number' => 'required|string|max:100|unique:special_orders,order_number,' . $specialOrder->id,
            'order_date' => 'required|date',
            'subject' => 'required|string|max:500',
            'content' => 'nullable|string',
            'effective_date' => 'nullable|date',
            'status' => 'required|in:draft,active,cancelled',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        if ($request->hasFile('attachment')) {
            if ($specialOrder->attachment_path) {
                Storage::disk('public')->delete($specialOrder->attachment_path);
            }
            $validated['attachment_path'] = $request->file('attachment')->store('special-orders', 'public');
        }

        $validated['content'] = $validated['content'] ?? '';

        $specialOrder->update($validated);

        return redirect()->route('special-orders.index')
            ->with('success', 'อัปเดตคำสั่งโรงเรียน (เฉพาะ) เรียบร้อยแล้ว');
    }

    public function destroy(SpecialOrder $specialOrder)
    {
        if ($specialOrder->attachment_path) {
            Storage::disk('public')->delete($specialOrder->attachment_path);
        }

        $specialOrder->delete();

        return redirect()->route('special-orders.index')
            ->with('success', 'ลบคำสั่งโรงเรียน (เฉพาะ) เรียบร้อยแล้ว');
    }

    public function exportPdf(SpecialOrder $specialOrder)
    {
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('special-orders.pdf', compact('specialOrder'));
        return $pdf->stream('special-order-' . $specialOrder->order_number . '.pdf');
    }
}
