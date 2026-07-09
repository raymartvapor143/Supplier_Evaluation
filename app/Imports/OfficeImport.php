<?php

namespace App\Imports;

use App\Models\Office;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class OfficeImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {

            // Skip header row
            if ($index == 0) continue;

            Office::create([
                'abbreviation' => $row[0] ?? null,
                'name'  => $row[1] ?? null,
                'head'         => $row[2] ?? null,
                'designation'  => $row[3] ?? null,
                'responsibility_number' => $row[4] ?? null,
            ]);
        }
    }
}
