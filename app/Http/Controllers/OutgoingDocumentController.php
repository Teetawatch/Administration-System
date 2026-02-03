<?php

namespace App\Http\Controllers;

use App\Models\OutgoingDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class OutgoingDocumentController extends Controller
{
    public function exportPdf(Request $request)
    {
        $ids = $request->input('document_ids', []);

        if (empty($ids)) {
            return redirect()->back()->with('error', 'กรุณาเลือกรายการที่ต้องการพิมพ์');
        }

        $documents = OutgoingDocument::whereIn('id', $ids)
            ->orderBy('document_date', 'desc')
            ->get();

        $pdf = Pdf::loadView('outgoing-documents.pdf', compact('documents'))
            ->setPaper('a4', 'portrait');
        return $pdf->stream('outgoing-documents-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = OutgoingDocument::with('creator')->latest();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('document_number', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('to_recipient', 'like', "%{$search}%");
            });
        }

        // Filter by department
        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        if ($request->filled('urgency')) {
            $query->where('urgency', $request->urgency);
        }

        $documents = $query->paginate(15)->withQueryString();
        $departments = OutgoingDocument::distinct()->pluck('department')->filter();

        return view('outgoing-documents.index', compact('documents', 'departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Auto-generate document number
        // Reset count every year (based on document_date year)
        $currentYear = date('Y');

        // 1. Calculate next number for Normal Documents (Format: just number e.g. 1, 2, 3)
        $lastNormal = OutgoingDocument::where('is_secret', false)
            ->whereYear('document_date', $currentYear)
            ->latest('id')
            ->first();

        $runningNumberNormal = 1;
        if ($lastNormal) {
            // Extract number - could be "1", "2" or old format "1/2569"
            if (is_numeric($lastNormal->document_number)) {
                $runningNumberNormal = intval($lastNormal->document_number) + 1;
            } elseif (preg_match('/^(\d+)/', $lastNormal->document_number, $matches)) {
                $runningNumberNormal = intval($matches[1]) + 1;
            }
        }
        $nextNormal = (string) $runningNumberNormal;

        // 2. Calculate next number for Secret Documents (Format: ลับ 1, ลับ 2)
        $lastSecret = OutgoingDocument::where('is_secret', true)
            ->whereYear('document_date', $currentYear)
            ->latest('id')
            ->first();
            
        $runningNumberSecret = 1;
        if ($lastSecret) {
            // Extract number from "ลับ 1", "ลับ 2" or old format "ลับ 1/2569"
            if (preg_match('/ลับ\s*(\d+)/', $lastSecret->document_number, $matches)) {
                $runningNumberSecret = intval($matches[1]) + 1;
            }
        }
        $nextSecret = "ลับ " . $runningNumberSecret;

        return view('outgoing-documents.create', compact('nextNormal', 'nextSecret'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'document_number' => 'required|string|max:100|unique:outgoing_documents',
            'document_date' => 'required|date',
            'to_recipient' => 'required|string|max:255',
            'subject' => 'required|string|max:500',
            'urgency' => 'required|in:normal,urgent,very_urgent,most_urgent',
            'department' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:10240',
            'is_secret' => 'boolean',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['is_secret'] = $request->boolean('is_secret', false);

        if ($request->hasFile('attachment')) {
            $validated['attachment_path'] = $request->file('attachment')->store('outgoing-documents', 'public');
        }

        OutgoingDocument::create($validated);

        return redirect()->route('outgoing-documents.index')
            ->with('success', 'บันทึกเลขหนังสือส่งเรียบร้อยแล้ว');
    }

    /**
     * Display the specified resource.
     */
    public function show(OutgoingDocument $outgoingDocument)
    {
        return view('outgoing-documents.show', compact('outgoingDocument'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OutgoingDocument $outgoingDocument)
    {
        return view('outgoing-documents.edit', compact('outgoingDocument'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OutgoingDocument $outgoingDocument)
    {
        $validated = $request->validate([
            'document_number' => 'required|string|max:100|unique:outgoing_documents,document_number,' . $outgoingDocument->id,
            'document_date' => 'required|date',
            'to_recipient' => 'required|string|max:255',
            'subject' => 'required|string|max:500',
            'urgency' => 'required|in:normal,urgent,very_urgent,most_urgent',
            'department' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:10240',
        ]);

        if ($request->hasFile('attachment')) {
            // Delete old attachment
            if ($outgoingDocument->attachment_path) {
                Storage::disk('public')->delete($outgoingDocument->attachment_path);
            }
            $validated['attachment_path'] = $request->file('attachment')->store('outgoing-documents', 'public');
        }

        $outgoingDocument->update($validated);

        return redirect()->route('outgoing-documents.index')
            ->with('success', 'อัปเดตเลขหนังสือส่งเรียบร้อยแล้ว');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OutgoingDocument $outgoingDocument)
    {
        if ($outgoingDocument->attachment_path) {
            Storage::disk('public')->delete($outgoingDocument->attachment_path);
        }

        $outgoingDocument->delete();

        return redirect()->route('outgoing-documents.index')
            ->with('success', 'ลบเลขหนังสือส่งเรียบร้อยแล้ว');
    }
}
