<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PersonnelExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    private Collection $personnelGroups;

    public function __construct(Collection $personnelGroups)
    {
        $this->personnelGroups = $personnelGroups;
    }

    public function collection()
    {
        $flattened = collect();
        foreach ($this->personnelGroups as $department => $group) {
            foreach ($group as $person) {
                $flattened->push($person);
            }
        }
        return $flattened;
    }

    public function headings(): array
    {
        return [
            'ลำดับ',
            'รหัสพนักงาน',
            'ยศ',
            'ชื่อ',
            'นามสกุล',
            'ตำแหน่ง',
            'แผนก',
            'เบอร์โทรศัพท์',
            'อีเมล',
        ];
    }

    public function map($person): array
    {
        static $index = 1;
        
        return [
            $index++,
            $person->employee_id,
            $person->rank,
            $person->first_name,
            $person->last_name,
            $person->position,
            $person->department,
            $person->phone,
            $person->email,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
