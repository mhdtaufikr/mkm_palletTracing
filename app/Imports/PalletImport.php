<?php

namespace App\Imports;

use App\Models\Pallet;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PalletImport implements ToCollection,WithHeadingRow
{
    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        
        foreach ($rows as $row) {
            // Check if the date is null and set it to now() if true
            $date = $row['date'] ?? now();

            // If the date is not null, process it accordingly
            if (!is_null($row['date'])) {
                // Assuming $excelDate is the numeric representation of the date in your Excel file
                $excelDate = $row['date'];
                $acquisitionDate = Carbon::createFromFormat('m/d/Y', '01/01/1900')->addDays($excelDate - 2)->toDateString();
                $date = $acquisitionDate;
            }

            // Save the data to the Pallet model
            Pallet::create([
                'no_delivery' => $row['no_delivery'],
                'no_pallet' => $row['no_pallet'],
                'type_pallet' => $row['type_pallet'],
                'destination' => $row['destination'],
                'date' => $date,
            ]);
        }
    }
}
