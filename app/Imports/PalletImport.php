<?php

namespace App\Imports;

use App\Models\Pallet;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PalletImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        DB::beginTransaction();
        try {
            foreach ($rows as $row) {
               // Check if the date is null and set it to now() if true
            $date = $row['date'] ?? now();

            // If the date is not null, process it accordingly
            if (!is_null($row['date'])) {
                // Check if the date format is 'd/m/Y'
                if (preg_match('/\d{2}\/\d{2}\/\d{4}/', $row['date'])) {
                    // If the date format is 'd/m/Y', convert it to a valid date
                    $date = Carbon::createFromFormat('d/m/Y', $row['date'])->toDateString();
                } else {
                    // If the date format is not 'd/m/Y', assume it's in the Excel numeric representation format
                    $excelDate = $row['date'];
                    $acquisitionDate = Carbon::createFromFormat('m/d/Y', '01/01/1900')->addDays($excelDate - 2)->toDateString();
                    $date = $acquisitionDate;
                }
            }
                $checkExistingPallets = Pallet::where('no_pallet', $row['no_pallet'])->get();

                if ($checkExistingPallets->isEmpty()) {
                    // Create a new Pallet with status 1
                    Pallet::create([
                        'no_delivery' => $row['no_delivery'],
                        'no_pallet' => $row['no_pallet'],
                        'type_pallet' => $row['type_pallet'],
                        'destination' => $row['destination'],
                        'date' => $date,
                        'status' => 1,
                    ]);
                
                    // If the pallet is new, return success
                    DB::commit();
                } else {
                     // Check for destination validation
                     $validDestinationTJU = in_array($row['destination'], ['TJU']);
                     $validDestinationKRM = in_array($row['destination'], ['KRM']);
                     $validDestinationMKM = in_array($row['destination'], ['MKM']);
 
                     // Additional destination movement validations
                     $oldDestination = Pallet::where('no_pallet', $row['no_pallet'])
                         ->where('status', 1)
                         ->value('destination');
 
                     // Define the conditions for invalid destination movement
                     $invalidConditions = [
                         'KRM' => ['TJU'],
                         'TJU' => ['KRM'],
                         'MKM' => [], // MKM can be moved to any destination
                     ];
 
                     // Check if the new destination is in the list of invalid destinations for the old destination
                     if (in_array($row['destination'], $invalidConditions[$oldDestination])) {
                         // Destination validation failed, rollback the transaction
                         DB::rollBack();
                         $errorMessage = 'Error importing Pallet. Invalid destination for no_pallet ' . $row['no_pallet'];
                         session()->flash('failed', $errorMessage);
                         throw new \Exception($errorMessage); // Stop the import process
                     }
 
 
                 // Check if a record with the same no_pallet and destination exists with status 1
                 $existingPallets = Pallet::where('no_pallet', $row['no_pallet'])
                 ->where('status', 1)
                 ->get();
 
                 foreach ($existingPallets as $existingPallet) {
                     // Check if the destination matches the new data
                     if ($existingPallet->destination == $row['destination']) {
                         // Destination validation failed for existing pallet, rollback the transaction
                         DB::rollBack();
                         $errorMessage = 'Error importing Pallet. Duplicate destination found for no_pallet ' . $row['no_pallet'];
                         session()->flash('failed', $errorMessage);
                         throw new \Exception($errorMessage); // Stop the import process
                     }
                         // Update the existing record's status to 0
                         $existingPallet->update(['status' => 0]);
                     }
 
 
                 // Create a new Pallet with status 1
                 Pallet::create([
                     'no_delivery' => $row['no_delivery'],
                     'no_pallet' => $row['no_pallet'],
                     'type_pallet' => $row['type_pallet'],
                     'destination' => $row['destination'],
                     'date' => $date,
                     'status' => 1,
                 ]);
             }
 
             DB::commit();
                }


                   
        } catch (\Exception $e) {
            // An error occurred, rollback the transaction
            DB::rollBack();
            throw $e;
        }
    }
}
