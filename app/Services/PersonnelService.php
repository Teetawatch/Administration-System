<?php

namespace App\Services;

use App\Models\Personnel;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

/**
 * Service class for Personnel business logic.
 * 
 * Following software-architecture best practices:
 * - Separation of Concerns: Business logic separated from controllers
 * - Single Responsibility: Each method has one clear purpose
 * - Reusable: Can be used by multiple controllers or commands
 */
class PersonnelService
{
    /**
     * Custom department sort order.
     */
    private const DEPARTMENT_ORDER = [
        'ส่วนบังคับบัญชา',
        'แผนกปกครอง',
        'แผนกศึกษา',
        'แผนกสนับสนุน',
        'ฝ่ายธุรการ',
        'ฝ่ายการเงิน',
    ];

    /**
     * Get personnel grouped by department with custom sort order.
     *
     * @param string|null $search Search term
     * @param string|null $department Department filter
     * @param string|null $status Status filter
     * @param string $viewMode View mode ('department' or 'all')
     * @return Collection<string, Collection<Personnel>>
     */
    public function getPersonnelByDepartment(
        ?string $search = null,
        ?string $department = null,
        ?string $status = null,
        string $viewMode = 'department'
    ): Collection {
        $query = Personnel::query()
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc');

        // Apply search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('employee_id', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('position', 'like', "%{$search}%");
            });
        }

        // Apply department filter
        if ($department) {
            $query->where('department', $department);
        }

        // Apply status filter
        if ($status) {
            $query->where('status', $status);
        }

        $allPersonnel = $query->get();

        // Return based on view mode
        if ($viewMode === 'all') {
            return collect(['รายชื่อทั้งหมด' => $allPersonnel]);
        }

        return $this->groupByDepartmentWithCustomOrder($allPersonnel);
    }

    /**
     * Get active personnel grouped by department.
     *
     * @return Collection<string, Collection<Personnel>>
     */
    public function getActivePersonnelByDepartment(): Collection
    {
        $personnel = Personnel::active()
            ->orderBy('sort_order', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->groupByDepartmentWithCustomOrder($personnel);
    }

    /**
     * Group personnel collection by department with custom sort order.
     *
     * @param Collection<Personnel> $personnel
     * @return Collection<string, Collection<Personnel>>
     */
    private function groupByDepartmentWithCustomOrder(Collection $personnel): Collection
    {
        $grouped = $personnel->groupBy(fn($item) => $item->department ?: 'ไม่ระบุฝ่าย');

        return $grouped->sortKeysUsing(function ($key1, $key2) {
            $pos1 = array_search($key1, self::DEPARTMENT_ORDER);
            $pos2 = array_search($key2, self::DEPARTMENT_ORDER);

            // If both are in custom list, compare positions
            if ($pos1 !== false && $pos2 !== false) {
                return $pos1 - $pos2;
            }

            // If one is in list, it comes first
            if ($pos1 !== false)
                return -1;
            if ($pos2 !== false)
                return 1;

            // If neither, sort alphabetically
            return strcmp($key1, $key2);
        });
    }

    /**
     * Create a new personnel record.
     *
     * @param array<string, mixed> $data Validated data
     * @param UploadedFile|null $photo Photo file
     * @return Personnel
     */
    public function create(array $data, ?UploadedFile $photo = null): Personnel
    {
        if ($photo) {
            $data['photo_path'] = $photo->store('personnel-photos', 'public');
        }

        $data['sort_order'] = (Personnel::max('sort_order') ?? 0) + 1;

        return Personnel::create($data);
    }

    /**
     * Update an existing personnel record.
     *
     * @param Personnel $personnel
     * @param array<string, mixed> $data Validated data
     * @param UploadedFile|null $photo New photo file
     * @return Personnel
     */
    public function update(Personnel $personnel, array $data, ?UploadedFile $photo = null): Personnel
    {
        if ($photo) {
            // Delete old photo if exists
            if ($personnel->photo_path) {
                Storage::disk('public')->delete($personnel->photo_path);
            }
            $data['photo_path'] = $photo->store('personnel-photos', 'public');
        }

        $personnel->update($data);

        return $personnel->fresh();
    }

    /**
     * Delete a personnel record and their photo.
     *
     * @param Personnel $personnel
     * @return bool
     */
    public function delete(Personnel $personnel): bool
    {
        if ($personnel->photo_path) {
            Storage::disk('public')->delete($personnel->photo_path);
        }

        return $personnel->delete() ?? false;
    }

    /**
     * Reorder personnel records.
     *
     * @param array<int> $ids Array of personnel IDs in new order
     * @return void
     */
    public function reorder(array $ids): void
    {
        foreach ($ids as $index => $id) {
            Personnel::where('id', $id)->update(['sort_order' => $index + 1]);
        }
    }

    /**
     * Get all unique departments.
     *
     * @return Collection<string>
     */
    public function getDepartments(): Collection
    {
        return Personnel::distinct()->pluck('department')->filter();
    }
}
