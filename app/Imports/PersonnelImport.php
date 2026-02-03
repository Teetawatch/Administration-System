<?php

namespace App\Imports;

use App\Models\Personnel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;

class PersonnelImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Skip if vital data is missing
        if (!isset($row['first_name']) || !isset($row['last_name'])) {
            return null;
        }

        // Auto-generate ID if not provided
        $employeeId = $row['employee_id'] ?? null;
        if (!$employeeId) {
             // Simple fallback ID generation similar to previous logic
             // Ideally we'd validte uniqueness or use the controller logic, but for import
             // we'll try to generate a safe unique ID based on timestamp component to avoid collision in bulk? 
             // Or just simple increment.
             // Let's rely on database auto-increment if we didn't enforce string ID, 
             // but employee_id is a string field.
             // Let's generate a random-ish ID or based on count.
             $count = Personnel::count() + 1;
             $employeeId = 'EMP-' . str_pad($count . rand(10,99), 6, '0', STR_PAD_LEFT); 
        }

        return new Personnel([
            'employee_id' => $employeeId,
            'rank'        => $row['rank'] ?? null,
            'first_name'  => $row['first_name'],
            'last_name'   => $row['last_name'],
            'position'    => $row['position'] ?? null,
            'department'  => $row['department'] ?? null,
            'phone'       => $row['phone'] ?? null,
            'email'       => $row['email'] ?? null,
            'status'      => 'active', // Default status as requested
            'created_by'  => Auth::id() ?? 1,
            // 'hire_date' => ?? - Optional
        ]);
    }
}
