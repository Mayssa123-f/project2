<?php

namespace App\Imports;

use App\Models\Flight;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class FlightImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        Log::info('Row keys: ' . implode(', ', array_keys($row)));
        $departure_time = is_numeric($row['departure_time'])
            ? Date::excelToDateTimeObject($row['departure_time'])->format('Y-m-d H:i:s')
            : $row['departure_time'];

        $arrival_time = is_numeric($row['arrival_time'])
            ? Date::excelToDateTimeObject($row['arrival_time'])->format('Y-m-d H:i:s')
            : $row['arrival_time'];

        return new Flight([
            'number' => $row['number'],
            'departure_city' => $row['departure_city'],
            'arrival_city' => $row['arrival_city'],
            'departure_time' => $departure_time,
            'arrival_time' => $arrival_time,
        ]);
    }
}
