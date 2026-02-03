<?php

namespace App\Http\Controllers;

use App\Models\Personnel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PersonnelImport;
use Barryvdh\DomPDF\Facade\Pdf;

class PersonnelController extends Controller
{
    public function exportPdf()
    {
        $personnelByDepartment = Personnel::active()
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($item) {
                return $item->department ?: 'ไม่ระบุฝ่าย';
            });

        $pdf = Pdf::loadView('personnel.pdf', compact('personnelByDepartment'));
        return $pdf->stream('personnel-' . date('Y-m-d') . '.pdf');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        Excel::import(new PersonnelImport, $request->file('file'));

        return redirect()->route('personnel.index')->with('success', 'นำเข้าข้อมูลจาก Excel เรียบร้อยแล้ว');
    }

    public function downloadTemplate()
    {
        return Excel::download(new \App\Exports\PersonnelTemplateExport, 'personnel_template.xlsx');
    }
    public function index(Request $request)
    {
        // Fetch all personnel ordered by sort_order
        $query = Personnel::orderBy('sort_order', 'asc')->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('employee_id', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%");
            });
        }

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Get all personnel
        $allPersonnel = $query->get();

        $viewMode = $request->input('view_mode', 'department');

        if ($viewMode === 'all') {
            $personnelByDepartment = collect(['รายชื่อทั้งหมด' => $allPersonnel]);
        } else {
            // Group by department
            $grouped = $allPersonnel->groupBy(function ($item) {
                return $item->department ?: 'ไม่ระบุฝ่าย';
            });

            // Custom sort order
            $customOrder = [
                'ส่วนบังคับบัญชา',
                'แผนกปกครอง',
                'แผนกศึกษา',
                'แผนกสนับสนุน',
                'ฝ่ายธุรการ',
                'ฝ่ายการเงิน'
            ];

            // Sort the collection keys based on custom order
            $personnelByDepartment = $grouped->sortKeysUsing(function ($key1, $key2) use ($customOrder) {
                $pos1 = array_search($key1, $customOrder);
                $pos2 = array_search($key2, $customOrder);

                // If both are in custom list, compare positions
                if ($pos1 !== false && $pos2 !== false) {
                    return $pos1 - $pos2;
                }

                // If one is in list, it comes first
                if ($pos1 !== false) return -1;
                if ($pos2 !== false) return 1;

                // If neither, sort alphabetically
                return strcmp($key1, $key2);
            });
        }

        // Departments list for filter dropdown
        $departments = Personnel::distinct()->pluck('department')->filter();

        return view('personnel.index', compact('personnelByDepartment', 'departments', 'viewMode'));
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:personnel,id',
        ]);

        foreach ($request->ids as $index => $id) {
            Personnel::where('id', $id)->update(['sort_order' => $index + 1]);
        }

        return response()->json(['status' => 'success']);
    }

    public function destroy(Personnel $personnel)
    {
        if ($personnel->photo_path) {
            Storage::disk('public')->delete($personnel->photo_path);
        }

        $personnel->delete();

        return redirect()->route('personnel.index')
            ->with('success', 'ลบข้อมูลบุคลากรเรียบร้อยแล้ว');
    }
    public function create()
    {
        return view('personnel.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|string|unique:personnel,employee_id',
            'rank' => 'nullable|string',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'position' => 'nullable|string',
            'department' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'hire_date' => 'nullable|date',
            'status' => 'required|in:active,inactive,retired',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('personnel-photos', 'public');
            $validated['photo_path'] = $path;
        }

        $validated['sort_order'] = Personnel::max('sort_order') + 1;

        Personnel::create($validated);

        return redirect()->route('personnel.index')
            ->with('success', 'เพิ่มข้อมูลบุคลากรเรียบร้อยแล้ว');
    }

    public function show(Personnel $personnel)
    {
        return view('personnel.show', compact('personnel'));
    }

    public function edit(Personnel $personnel)
    {
        return view('personnel.edit', compact('personnel'));
    }

    public function update(Request $request, Personnel $personnel)
    {
        $validated = $request->validate([
            'employee_id' => 'required|string|unique:personnel,employee_id,' . $personnel->id,
            'rank' => 'nullable|string',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'position' => 'nullable|string',
            'department' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'hire_date' => 'nullable|date',
            'status' => 'required|in:active,inactive,retired',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($personnel->photo_path) {
                Storage::disk('public')->delete($personnel->photo_path);
            }
            $path = $request->file('photo')->store('personnel-photos', 'public');
            $validated['photo_path'] = $path;
        }

        $personnel->update($validated);

        return redirect()->route('personnel.index')
            ->with('success', 'อัปเดตข้อมูลบุคลากรเรียบร้อยแล้ว');
    }
}
