<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreActivityRequest;
use App\Http\Requests\UpdateActivityRequest;
use App\Models\Activity;
use App\Services\ActivityService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Controller for managing Activities.
 * 
 * Following best practices:
 * - php-pro: Type hints, return types, modern PHP 8 features
 * - software-architecture: Separation of concerns via service class
 * - database-architect: Optimized queries via service layer
 */
class ActivityController extends Controller
{
    public function __construct(
        private readonly ActivityService $activityService
    ) {
    }

    /**
     * Display a listing of activities.
     */
    public function index(Request $request): View
    {
        $activities = $this->activityService->getPaginatedActivities(
            search: $request->input('search'),
            status: $request->input('status'),
            date: $request->input('date')
        );

        return view('activities.index', compact('activities'));
    }

    /**
     * Show the form for creating a new activity.
     */
    public function create(): View
    {
        $personnel = $this->activityService->getAvailablePersonnelForCreate();
        $departments = $this->activityService->getDepartmentsFromPersonnel($personnel);
        $personnelByDepartment = $this->activityService->groupPersonnelByDepartment($personnel);

        return view('activities.create', compact('personnel', 'departments', 'personnelByDepartment'));
    }

    /**
     * Store a newly created activity in storage.
     */
    public function store(StoreActivityRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $this->activityService->create(
            data: $validated,
            userId: Auth::id(),
            participantIds: $request->input('participants')
        );

        return redirect()->route('activities.index')
            ->with('success', 'บันทึกกิจกรรมเรียบร้อยแล้ว');
    }

    /**
     * Display the specified activity.
     */
    public function show(Activity $activity): View
    {
        $activity->load('participants');

        return view('activities.show', compact('activity'));
    }

    /**
     * Show the form for editing the specified activity.
     */
    public function edit(Activity $activity): View
    {
        $personnel = $this->activityService->getAvailablePersonnelForEdit($activity);
        $departments = $this->activityService->getDepartmentsFromPersonnel($personnel);
        $personnelByDepartment = $this->activityService->groupPersonnelByDepartment($personnel);

        return view('activities.edit', compact('activity', 'personnel', 'departments', 'personnelByDepartment'));
    }

    /**
     * Update the specified activity in storage.
     */
    public function update(UpdateActivityRequest $request, Activity $activity): RedirectResponse
    {
        $validated = $request->validated();

        $this->activityService->update(
            activity: $activity,
            data: $validated,
            participantIds: $request->input('participants')
        );

        return redirect()->route('activities.index')
            ->with('success', 'อัปเดตกิจกรรมเรียบร้อยแล้ว');
    }

    /**
     * Remove the specified activity from storage.
     */
    public function destroy(Activity $activity): RedirectResponse
    {
        $this->activityService->delete($activity);

        return redirect()->route('activities.index')
            ->with('success', 'ลบกิจกรรมเรียบร้อยแล้ว');
    }

    /**
     * Export participants report to PDF.
     */
    public function exportParticipantsReport(): Response
    {
        $activities = $this->activityService->getAllActivitiesWithParticipants();
        $personnelWithoutActivity = $this->activityService->getPersonnelWithoutActivity();

        $pdf = Pdf::loadView('activities.participants-report-pdf', compact(
            'activities',
            'personnelWithoutActivity'
        ));

        return $pdf->stream('activity-participants-report-' . date('Y-m-d') . '.pdf');
    }
}
