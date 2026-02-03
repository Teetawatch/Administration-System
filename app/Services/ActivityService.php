<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\Personnel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Service class for Activity business logic.
 * 
 * Following software-architecture best practices:
 * - Separation of Concerns: Business logic separated from controllers
 * - Single Responsibility: Each method has one clear purpose
 * - Reusable: Can be used by multiple controllers or commands
 */
class ActivityService
{
    /**
     * Get paginated activities with filters.
     *
     * @param string|null $search Search term
     * @param string|null $status Status filter
     * @param string|null $date Date filter
     * @param int $perPage Items per page
     * @return LengthAwarePaginator
     */
    public function getPaginatedActivities(
        ?string $search = null,
        ?string $status = null,
        ?string $date = null,
        int $perPage = 15
    ): LengthAwarePaginator {
        $query = Activity::with('creator')->latest();

        if ($search) {
            $query->search($search);
        }

        if ($status) {
            $query->status($status);
        }

        if ($date) {
            $query->whereDate('start_date', $date);
        }

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Get all activities with participants for export.
     *
     * @return Collection<Activity>
     */
    public function getAllActivitiesWithParticipants(): Collection
    {
        return Activity::with('participants')
            ->orderBy('start_date', 'desc')
            ->get();
    }

    /**
     * Get personnel IDs who have participated in any activity.
     *
     * @return array<int>
     */
    public function getParticipatedPersonnelIds(): array
    {
        return DB::table('activity_personnel')
            ->distinct()
            ->pluck('personnel_id')
            ->toArray();
    }

    /**
     * Get available personnel for activity creation (not participated in other activities).
     *
     * @return Collection<Personnel>
     */
    public function getAvailablePersonnelForCreate(): Collection
    {
        $participatedIds = $this->getParticipatedPersonnelIds();

        return Personnel::active()
            ->whereNotIn('id', $participatedIds)
            ->orderBy('sort_order', 'asc')
            ->get();
    }

    /**
     * Get available personnel for activity edit.
     * Includes current participants and personnel not in other activities.
     *
     * @param Activity $activity
     * @return Collection<Personnel>
     */
    public function getAvailablePersonnelForEdit(Activity $activity): Collection
    {
        $participatedInOtherIds = DB::table('activity_personnel')
            ->where('activity_id', '!=', $activity->id)
            ->distinct()
            ->pluck('personnel_id')
            ->toArray();

        $currentParticipantIds = $activity->participants->pluck('id')->toArray();

        return Personnel::active()
            ->where(function (Builder $query) use ($participatedInOtherIds, $currentParticipantIds) {
                $query->whereIn('id', $currentParticipantIds)
                    ->orWhereNotIn('id', $participatedInOtherIds);
            })
            ->orderBy('sort_order', 'asc')
            ->get();
    }

    /**
     * Get personnel without any activity.
     *
     * @return Collection<Personnel>
     */
    public function getPersonnelWithoutActivity(): Collection
    {
        $participatedIds = $this->getParticipatedPersonnelIds();

        return Personnel::active()
            ->whereNotIn('id', $participatedIds)
            ->orderBy('sort_order', 'asc')
            ->get();
    }

    /**
     * Create a new activity with participants.
     *
     * @param array<string, mixed> $data Validated data
     * @param int $userId Creator user ID
     * @param array<int>|null $participantIds Participant personnel IDs
     * @return Activity
     */
    public function create(array $data, int $userId, ?array $participantIds = null): Activity
    {
        $data['created_by'] = $userId;

        $activity = Activity::create($data);

        if ($participantIds) {
            $activity->participants()->sync($participantIds);
        }

        return $activity->load('participants');
    }

    /**
     * Update an existing activity with participants.
     *
     * @param Activity $activity
     * @param array<string, mixed> $data Validated data
     * @param array<int>|null $participantIds Participant personnel IDs
     * @return Activity
     */
    public function update(Activity $activity, array $data, ?array $participantIds = null): Activity
    {
        $activity->update($data);

        if ($participantIds !== null) {
            $activity->participants()->sync($participantIds);
        }

        return $activity->fresh()->load('participants');
    }

    /**
     * Delete an activity.
     *
     * @param Activity $activity
     * @return bool
     */
    public function delete(Activity $activity): bool
    {
        // Detach all participants first
        $activity->participants()->detach();

        return $activity->delete() ?? false;
    }

    /**
     * Group personnel by department.
     *
     * @param Collection<Personnel> $personnel
     * @return Collection<string, Collection<Personnel>>
     */
    public function groupPersonnelByDepartment(Collection $personnel): Collection
    {
        return $personnel->groupBy('department');
    }

    /**
     * Get unique departments from personnel collection.
     *
     * @param Collection<Personnel> $personnel
     * @return Collection<string>
     */
    public function getDepartmentsFromPersonnel(Collection $personnel): Collection
    {
        return $personnel->pluck('department')
            ->unique()
            ->filter()
            ->sort()
            ->values();
    }
}
