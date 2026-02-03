<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePersonnelRequest;
use App\Http\Requests\UpdatePersonnelRequest;
use App\Models\Personnel;
use App\Services\PersonnelService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PersonnelImport;

/**
 * Controller for managing Personnel records.
 * 
 * Following best practices:
 * - php-pro: Type hints, return types, modern PHP 8 features
 * - software-architecture: Separation of concerns via service class
 * - database-architect: Optimized queries via service layer
 */
class PersonnelController extends Controller
{
    public function __construct(
        private readonly PersonnelService $personnelService
    ) {
    }

    /**
     * Export personnel data to PDF.
     */
    public function exportPdf(): Response
    {
        $personnelByDepartment = $this->personnelService->getActivePersonnelByDepartment();

        $pdf = Pdf::loadView('personnel.pdf', compact('personnelByDepartment'));

        return $pdf->stream('personnel-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Import personnel data from Excel file.
     */
    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        Excel::import(new PersonnelImport, $request->file('file'));

        return redirect()->route('personnel.index')
            ->with('success', 'นำเข้าข้อมูลจาก Excel เรียบร้อยแล้ว');
    }

    /**
     * Download Excel template for importing personnel.
     */
    public function downloadTemplate(): mixed
    {
        return Excel::download(new \App\Exports\PersonnelTemplateExport, 'personnel_template.xlsx');
    }

    /**
     * Display a listing of personnel.
     */
    public function index(Request $request): View
    {
        $viewMode = $request->input('view_mode', 'department');

        $personnelByDepartment = $this->personnelService->getPersonnelByDepartment(
            search: $request->input('search'),
            department: $request->input('department'),
            status: $request->input('status'),
            viewMode: $viewMode
        );

        $departments = $this->personnelService->getDepartments();

        return view('personnel.index', compact('personnelByDepartment', 'departments', 'viewMode'));
    }

    /**
     * Reorder personnel records via drag-and-drop.
     */
    public function reorder(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:personnel,id',
        ]);

        $this->personnelService->reorder($request->ids);

        return response()->json(['status' => 'success']);
    }

    /**
     * Remove the specified personnel from storage.
     */
    public function destroy(Personnel $personnel): RedirectResponse
    {
        $this->personnelService->delete($personnel);

        return redirect()->route('personnel.index')
            ->with('success', 'ลบข้อมูลบุคลากรเรียบร้อยแล้ว');
    }

    /**
     * Show the form for creating a new personnel.
     */
    public function create(): View
    {
        return view('personnel.create');
    }

    /**
     * Store a newly created personnel in storage.
     */
    public function store(StorePersonnelRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $this->personnelService->create(
            data: $validated,
            photo: $request->file('photo')
        );

        return redirect()->route('personnel.index')
            ->with('success', 'เพิ่มข้อมูลบุคลากรเรียบร้อยแล้ว');
    }

    /**
     * Display the specified personnel.
     */
    public function show(Personnel $personnel): View
    {
        return view('personnel.show', compact('personnel'));
    }

    /**
     * Show the form for editing the specified personnel.
     */
    public function edit(Personnel $personnel): View
    {
        return view('personnel.edit', compact('personnel'));
    }

    /**
     * Update the specified personnel in storage.
     */
    public function update(UpdatePersonnelRequest $request, Personnel $personnel): RedirectResponse
    {
        $validated = $request->validated();

        $this->personnelService->update(
            personnel: $personnel,
            data: $validated,
            photo: $request->file('photo')
        );

        return redirect()->route('personnel.index')
            ->with('success', 'อัปเดตข้อมูลบุคลากรเรียบร้อยแล้ว');
    }
}
