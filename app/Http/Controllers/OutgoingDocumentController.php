<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOutgoingDocumentRequest;
use App\Http\Requests\UpdateOutgoingDocumentRequest;
use App\Models\OutgoingDocument;
use App\Services\OutgoingDocumentService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Controller for managing Outgoing Documents.
 * 
 * Following best practices:
 * - php-pro: Type hints, return types, modern PHP 8 features
 * - software-architecture: Separation of concerns via service class
 * - database-architect: Optimized queries via service layer
 */
class OutgoingDocumentController extends Controller
{
    public function __construct(
        private readonly OutgoingDocumentService $documentService
    ) {
    }

    /**
     * Export selected documents to PDF.
     */
    public function exportPdf(Request $request): RedirectResponse|Response
    {
        $ids = $request->input('document_ids', []);

        if (empty($ids)) {
            return redirect()->back()->with('error', 'กรุณาเลือกรายการที่ต้องการพิมพ์');
        }

        $documents = $this->documentService->getDocumentsByIds($ids);

        $pdf = Pdf::loadView('outgoing-documents.pdf', compact('documents'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('outgoing-documents-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Display a listing of the documents.
     */
    public function index(Request $request): View
    {
        $documents = $this->documentService->getPaginatedDocuments(
            search: $request->input('search'),
            department: $request->input('department'),
            urgency: $request->input('urgency')
        );

        $departments = $this->documentService->getDepartments();

        return view('outgoing-documents.index', compact('documents', 'departments'));
    }

    /**
     * Show the form for creating a new document.
     */
    public function create(): View
    {
        $nextNormal = $this->documentService->getNextNormalDocumentNumber();
        $nextSecret = $this->documentService->getNextSecretDocumentNumber();

        return view('outgoing-documents.create', compact('nextNormal', 'nextSecret'));
    }

    /**
     * Store a newly created document in storage.
     */
    public function store(StoreOutgoingDocumentRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_secret'] = $request->boolean('is_secret', false);

        $this->documentService->create(
            data: $validated,
            userId: Auth::id(),
            attachment: $request->file('attachment')
        );

        return redirect()->route('outgoing-documents.index')
            ->with('success', 'บันทึกเลขหนังสือส่งเรียบร้อยแล้ว');
    }

    /**
     * Display the specified document.
     */
    public function show(OutgoingDocument $outgoingDocument): View
    {
        return view('outgoing-documents.show', compact('outgoingDocument'));
    }

    /**
     * Show the form for editing the specified document.
     */
    public function edit(OutgoingDocument $outgoingDocument): View
    {
        return view('outgoing-documents.edit', compact('outgoingDocument'));
    }

    /**
     * Update the specified document in storage.
     */
    public function update(
        UpdateOutgoingDocumentRequest $request,
        OutgoingDocument $outgoingDocument
    ): RedirectResponse {
        $validated = $request->validated();

        $this->documentService->update(
            document: $outgoingDocument,
            data: $validated,
            attachment: $request->file('attachment')
        );

        return redirect()->route('outgoing-documents.index')
            ->with('success', 'อัปเดตเลขหนังสือส่งเรียบร้อยแล้ว');
    }

    /**
     * Remove the specified document from storage.
     */
    public function destroy(OutgoingDocument $outgoingDocument): RedirectResponse
    {
        $this->documentService->delete($outgoingDocument);

        return redirect()->route('outgoing-documents.index')
            ->with('success', 'ลบเลขหนังสือส่งเรียบร้อยแล้ว');
    }
}
