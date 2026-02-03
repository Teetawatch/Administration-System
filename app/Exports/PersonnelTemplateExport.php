<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;

class PersonnelTemplateExport implements WithHeadings
{
    public function headings(): array
    {
        return [
            'employee_id',
            'rank',
            'first_name',
            'last_name',
            'position',
            'department',
            'phone',
            'email',
        ];
    }
}
