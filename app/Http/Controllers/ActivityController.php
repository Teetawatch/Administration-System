<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Personnel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::with('creator')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('activity_name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('start_date', $request->date);
        }

        $activities = $query->paginate(15)->withQueryString();

        return view('activities.index', compact('activities'));
    }

    public function create()
    {
        // Get personnel IDs who have already participated in any activity
        $participatedIds = \DB::table('activity_personnel')
            ->distinct()
            ->pluck('personnel_id')
            ->toArray();
        
        // Get only personnel who haven't participated in any activity yet
        $personnel = Personnel::active()
            ->whereNotIn('id', $participatedIds)
            ->orderBy('sort_order', 'asc')
            ->get();
        
        $departments = $personnel->pluck('department')->unique()->filter()->sort()->values();
        $personnelByDepartment = $personnel->groupBy('department');
        return view('activities.create', compact('personnel', 'departments', 'personnelByDepartment'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'activity_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,ongoing,completed,cancelled',
            'priority' => 'required|integer|min:1|max:5',
            'participants' => 'nullable|array',
            'participants.*' => 'exists:personnel,id',
        ]);

        $validated['created_by'] = Auth::id();

        $activity = Activity::create($validated);

        if ($request->has('participants')) {
            $activity->participants()->sync($request->participants);
        }

        return redirect()->route('activities.index')
            ->with('success', 'บันทึกกิจกรรมเรียบร้อยแล้ว');
    }

    public function show(Activity $activity)
    {
        $activity->load('participants');
        return view('activities.show', compact('activity'));
    }

    public function edit(Activity $activity)
    {
        // Get personnel IDs who have participated in OTHER activities (not this one)
        $participatedInOtherIds = \DB::table('activity_personnel')
            ->where('activity_id', '!=', $activity->id)
            ->distinct()
            ->pluck('personnel_id')
            ->toArray();
        
        // Get current participants of this activity
        $currentParticipantIds = $activity->participants->pluck('id')->toArray();
        
        // Get personnel who either:
        // 1. Are current participants of this activity
        // 2. Haven't participated in any activity yet
        $personnel = Personnel::active()
            ->where(function($query) use ($participatedInOtherIds, $currentParticipantIds) {
                $query->whereIn('id', $currentParticipantIds)
                      ->orWhereNotIn('id', $participatedInOtherIds);
            })
            ->orderBy('sort_order', 'asc')
            ->get();
        
        $departments = $personnel->pluck('department')->unique()->filter()->sort()->values();
        $personnelByDepartment = $personnel->groupBy('department');
        return view('activities.edit', compact('activity', 'personnel', 'departments', 'personnelByDepartment'));
    }

    public function update(Request $request, Activity $activity)
    {
        $validated = $request->validate([
            'activity_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,ongoing,completed,cancelled',
            'priority' => 'required|integer|min:1|max:5',
            'participants' => 'nullable|array',
            'participants.*' => 'exists:personnel,id',
        ]);

        $activity->update($validated);

        if ($request->has('participants')) {
            $activity->participants()->sync($request->participants);
        }

        return redirect()->route('activities.index')
            ->with('success', 'อัปเดตกิจกรรมเรียบร้อยแล้ว');
    }

    public function destroy(Activity $activity)
    {
        $activity->delete();

        return redirect()->route('activities.index')
            ->with('success', 'ลบกิจกรรมเรียบร้อยแล้ว');
    }

    public function exportParticipantsReport()
    {
        // Get all activities with their participants
        $activities = Activity::with('participants')
            ->orderBy('start_date', 'desc')
            ->get();

        // Get personnel IDs who have participated in at least one activity
        $participatedIds = \DB::table('activity_personnel')
            ->distinct()
            ->pluck('personnel_id')
            ->toArray();

        // Get personnel who haven't participated in any activity
        $personnelWithoutActivity = Personnel::active()
            ->whereNotIn('id', $participatedIds)
            ->orderBy('sort_order', 'asc')
            ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('activities.participants-report-pdf', compact('activities', 'personnelWithoutActivity'));
        
        return $pdf->stream('activity-participants-report-' . date('Y-m-d') . '.pdf');
    }
}
