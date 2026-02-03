<?php

namespace App\Http\Controllers;

use App\Models\SchoolOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SchoolOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = SchoolOrder::with('creator')->latest();

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

        return view('school-orders.index', compact('orders'));
    }

    public function create()
    {
        // Auto-generate order number (Format: Running/ThaiYear e.g. 1/2569)
        $currentThaiYear = date('Y') + 543;
        $lastOrder = SchoolOrder::where('order_number', 'LIKE', "%/{$currentThaiYear}")
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

        return view('school-orders.create', compact('nextOrderNumber'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_number' => 'required|string|max:100|unique:school_orders',
            'order_date' => 'required|date',
            'subject' => 'required|string|max:500',
            'content' => 'nullable|string',

            'effective_date' => 'nullable|date',
            'status' => 'nullable|in:draft,active,cancelled', // Made nullable
            'attachment' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['status'] = $validated['status'] ?? 'active'; // Default to active

        if ($request->hasFile('attachment')) {
            $validated['attachment_path'] = $request->file('attachment')->store('school-orders', 'public');
        }

        $validated['content'] = $validated['content'] ?? '';

        SchoolOrder::create($validated);

        return redirect()->route('school-orders.index')
            ->with('success', 'บันทึกคำสั่งโรงเรียนเรียบร้อยแล้ว');
    }

    public function show(SchoolOrder $schoolOrder)
    {
        return view('school-orders.show', compact('schoolOrder'));
    }

    public function edit(SchoolOrder $schoolOrder)
    {
        return view('school-orders.edit', compact('schoolOrder'));
    }

    public function update(Request $request, SchoolOrder $schoolOrder)
    {
        $validated = $request->validate([
            'order_number' => 'required|string|max:100|unique:school_orders,order_number,' . $schoolOrder->id,
            'order_date' => 'required|date',
            'subject' => 'required|string|max:500',
            'content' => 'nullable|string',
            'effective_date' => 'nullable|date',
            'status' => 'required|in:draft,active,cancelled',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        if ($request->hasFile('attachment')) {
            if ($schoolOrder->attachment_path) {
                Storage::disk('public')->delete($schoolOrder->attachment_path);
            }
            $validated['attachment_path'] = $request->file('attachment')->store('school-orders', 'public');
        }

        $validated['content'] = $validated['content'] ?? '';

        $schoolOrder->update($validated);

        return redirect()->route('school-orders.index')
            ->with('success', 'อัปเดตคำสั่งโรงเรียนเรียบร้อยแล้ว');
    }

    public function destroy(SchoolOrder $schoolOrder)
    {
        if ($schoolOrder->attachment_path) {
            Storage::disk('public')->delete($schoolOrder->attachment_path);
        }

        $schoolOrder->delete();

        return redirect()->route('school-orders.index')
            ->with('success', 'ลบคำสั่งโรงเรียนเรียบร้อยแล้ว');
    }

    public function exportPdf(SchoolOrder $schoolOrder)
    {
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('school-orders.pdf', compact('schoolOrder'));
        return $pdf->stream('school-order-' . $schoolOrder->order_number . '.pdf');
    }
}
