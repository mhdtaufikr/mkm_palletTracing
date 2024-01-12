<?php

namespace App\Http\Controllers;

use App\Models\Dropdown;
use Illuminate\Http\Request;
use App\Models\Pallet;
use App\Exports\ExcelExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PalletImport;
use Illuminate\Support\Facades\DB;
use Throwable;
class PalletController extends Controller
{

    public function index()
        {
            // Fetch pallet data grouped by no_pallet
            $palletData = Pallet::orderBy('created_at', 'desc')->where('status', '1')->get();

            // Fetch dropdown data
            $typePallet = Dropdown::where('category', 'Type Pallet')->get();
            $destinationPallet = Dropdown::where('category', 'Destination')->get();

            // Fetch all pallets for the initial load and encode it properly
            $allPallets = json_encode(Pallet::pluck('no_pallet', 'destination')->toArray());

            // Pass the data to the view
            return view("pallet.index", compact("palletData", "typePallet", "destinationPallet", "allPallets"));
        }



    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'no_delivery' => 'required|string|max:255',
            'date' => 'required|date',
            'no_pallet' => 'required|array',
            'no_pallet.*' => 'string|max:255',
            'destination' => 'required|string|max:255',
        ]);

        // Wrap the operation in a database transaction
        DB::beginTransaction();

        try {
            // Check if a record with the same no_delivery exists
            $existingDelivery = Pallet::where('no_delivery', $request->input('no_delivery'))->first();

            if ($existingDelivery) {
                // Rollback the transaction and return with an error message
                DB::rollBack();
                return redirect()->route('pallet.index')->with('failed', 'No delivery already exists')->withInput();
            }

            // Create an array to store pallet data
            $palletsData = [];

            // Iterate through each "No. Pallet" value and create/update Pallet instances
            foreach ($request->input('no_pallet') as $noPallet) {
                // Check if a record with the same no_pallet exists
                $existingPallet = Pallet::where('no_pallet', $noPallet)->where('status', '1')->first();

                if ($existingPallet) {
                    // Update the existing record's status to 0
                    $existingPallet->update(['status' => 0]);

                    // Check for destination validation against the old destination
                    $validDestination = in_array($request->input('destination'), ['TJU', 'KRM', 'MKM','KTBSP']);

                    if (!$validDestination || $request->input('destination') === $existingPallet->destination) {
                        // Destination validation failed, rollback the transaction
                        DB::rollBack();
                        return redirect()->route('pallet.index')->with('failed', 'Invalid destination')->withInput();
                    }
                }

                // Additional destination movement validations
                $oldDestination = $existingPallet ? $existingPallet->destination : null;

                // Define the conditions for invalid destination movement
                $invalidConditions = [
                    'KRM' => ['TJU', 'KTBSP'],
                    'TJU' => ['KRM', 'KTBSP'],
                    'KTBSP' => ['KRM', 'TJU'],
                    'MKM' => [], // MKM can be moved to any destination
                ];
                

                // Check if the new destination is in the list of invalid destinations for the old destination
                if ($oldDestination && in_array($request->input('destination'), $invalidConditions[$oldDestination])) {
                    // Destination validation failed, rollback the transaction
                    DB::rollBack();
                    return redirect()->route('pallet.index')->with('failed', 'Invalid destination')->withInput();
                }

                // Derive type_pallet from the first two characters of no_pallet
                $typePallet = $this->getTypePalletFromNoPallet($noPallet);

                // Create a new Pallet with status 1
                $palletsData[] = [
                    'no_delivery' => $request->input('no_delivery'),
                    'date' => $request->input('date'),
                    'no_pallet' => $noPallet,
                    'type_pallet' => $typePallet,
                    'destination' => $request->input('destination'),
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Insert the pallets data into the database
            Pallet::insert($palletsData);

            // Commit the transaction
            DB::commit();

            // Optionally, you can return a response or redirect to another page
            return redirect()->route('pallet.index')->with('status', 'Pallets created/updated successfully');
        } catch (\Exception $e) {
            // Something went wrong, rollback the transaction and handle the error
            DB::rollBack();
            return redirect()->route('pallet.index')->with('failed', 'An error occurred while saving the data')->withInput();
        }
    }

    /**
     * Derive type_pallet from the first two characters of no_pallet
     *
     * @param string $noPallet
     * @return string|null
     */
    private function getTypePalletFromNoPallet($noPallet)
    {
        $prefixMappings = [
            'EG' => 'Engine',
            'FA' => 'FA',
            'TM' => 'TM-Assy',
        ];

        $palletPrefix = substr($noPallet, 0, 2);

        return $prefixMappings[$palletPrefix] ?? null;
    }

    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'no_delivery' => 'required|string|max:255',
            'date' => 'required|date_format:Y-m-d',
            'destination' => 'required|string|max:255',
            'no_pallet' => 'required|string|max:255',
        ]);
    
        // Find the Pallet by ID
        $pallet = Pallet::findOrFail($id);
    
        // Get the original attributes
        $originalAttributes = $pallet->getOriginal();
    
        // Derive type_pallet from the first two characters of no_pallet
        $typePallet = $this->getTypePalletFromNoPallet($request->input('no_pallet'));
    
        // Update the Pallet with the new data
        $pallet->no_delivery = $request->input('no_delivery');
        $pallet->date = $request->input('date');
        $pallet->type_pallet = $typePallet; // Assign derived type_pallet
        $pallet->destination = $request->input('destination');
        $pallet->no_pallet = $request->input('no_pallet');
    
        // Check if any attributes are dirty
        if ($pallet->isDirty()) {
            // Optionally, you can add additional logic or events here
    
            // Save the Pallet only if there are changes
            $pallet->save();
    
            return redirect()->route('pallet.index')->with('status', 'Pallet updated successfully');
        } else {
            return redirect()->route('pallet.index')->with('failed', 'No changes made to the Pallet');
        }
    }
    
    
    
        
    public function delete($id)
    {
        // Find the Pallet by ID
        $pallet = Pallet::findOrFail($id);
    
        // Delete the Pallet
        $pallet->delete();
    
        // Optionally, you can add additional logic or events here
    
        return redirect()->route('pallet.index')->with('status', 'Pallet deleted successfully');
    }

    public function excelFormat()
{
    $export = new ExcelExport('ex : 31/12/2023');

    return response()->stream(
        function () use ($export) {
            $export->export()->save('php://output');
        },
        200,
        [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="Format Pallet Import.xlsx"',
        ]
    );
}

    

    public function excelData(Request $request)
    {
        $request->validate([
            'excel-file' => 'required|file|mimes:xlsx',
        ]);

        try {
            // Start a database transaction
            DB::beginTransaction();

            // Import data using AssetImport class
            Excel::import(new PalletImport, $request->file('excel-file'));

            // If everything is successful, commit the transaction
            DB::commit();

            return redirect()->route('pallet.index')->with('status', 'Pallets imported successfully');
        } catch (Throwable $e) {
            // If an error occurs, rollback the transaction
            DB::rollBack();

            // Log or handle the error as needed
            // You can also use $e->getMessage() to get the error message
            $errorMessage = $e->getMessage();

            if ($errorMessage) {
                // If there's a specific error message, display it
                return redirect()->route('pallet.index')->with('failed', $errorMessage);
            }
            return redirect()->route('pallet.index')->with('failed', 'Error importing Pallet. Please check the data format.');
        }
    }

    public function palletSearch(Request $request)
    {
        $request->validate([
            'searchBy' => 'required|in:no_pallet,date,storage', // Include 'storage' in the validation rule
            'palletNo' => 'nullable|string',
            'dateFrom' => 'nullable|date',
            'dateTo' => 'nullable|date|after_or_equal:dateFrom',
        ]);
        
        $typePallet = Dropdown::where('category', 'Type Pallet')->get();
        $destinationPallet = Dropdown::where('category', 'Destination')->get();

        // Extract request data
        $searchBy = $request->input('searchBy');
        $palletNo = $request->input('palletNo');
        $dateFrom = $request->input('dateFrom');
        $dateTo = $request->input('dateTo');

        // Query based on searchBy
        $query = Pallet::query();
        if ($searchBy === 'no_pallet') {
            // Search by no_pallet if palletNo is provided
            if ($palletNo) {
                $query->where('no_pallet', $palletNo)->where('status', '1');
            }
            } elseif ($searchBy === 'date' && $dateFrom && $dateTo) {
                $query->whereBetween('date', [$dateFrom, $dateTo]);
            } elseif ($searchBy === 'storage' && $request->has('storage')) { // New condition for Storage
                $query->where('destination', $request->storage);
            }
        

        // Add any additional conditions as needed

        // Execute the query
        $palletData = $query->orderBy('date', 'desc')->get();

        // Do something with the results
        return view("pallet.index", compact("palletData", "typePallet", "destinationPallet"));
    }
    public function getNoPallets($destination)
    {
          // Fetch all no_pallet values based on destination and status
          $allNoPallets = Pallet::where('status', 1);
    
          // Apply additional conditions based on the destination
          if ($destination == 'TJU') {
              $allNoPallets->where(function ($query) use ($destination) {
                  $query->where('destination', '!=', $destination)
                      ->Where('destination','MKM');
              });
          } elseif ($destination == 'KRM') {
              $allNoPallets->where(function ($query) use ($destination) {
                  $query->where('destination', '!=', $destination)
                      ->Where('destination','MKM');
              });
          } elseif ($destination == 'KTBSP') {
              $allNoPallets->where(function ($query) use ($destination) {
                  $query->where('destination', '!=', $destination)
                      ->Where('destination','MKM');
              });
          } elseif ($destination == 'MKM') {
              $allNoPallets->where('destination', '!=', $destination);
          }
      
          // Order the results by no_pallet
          $noPallets = $allNoPallets->orderBy('no_pallet')->pluck('no_pallet');
      
          return response()->json($noPallets);
    }

    public function getAllNoPallets($destination)
    {
        // Fetch all no_pallet values based on destination and status
        $allNoPallets = Pallet::where('status', 1);
    
        // Apply additional conditions based on the destination
        if ($destination == 'TJU') {
            $allNoPallets->where(function ($query) use ($destination) {
                $query->where('destination', '!=', $destination)
                    ->Where('destination','MKM');
            });
        } elseif ($destination == 'KRM') {
            $allNoPallets->where(function ($query) use ($destination) {
                $query->where('destination', '!=', $destination)
                    ->Where('destination','MKM');
            });
        } elseif ($destination == 'KTBSP') {
            $allNoPallets->where(function ($query) use ($destination) {
                $query->where('destination', '!=', $destination)
                    ->Where('destination','MKM');
            });
        } elseif ($destination == 'MKM') {
            $allNoPallets->where('destination', '!=', $destination);
        }
    
        // Order the results by no_pallet
        $allNoPallets = $allNoPallets->orderBy('no_pallet')->pluck('no_pallet');
    
        return response()->json($allNoPallets);
    }
    

    
}
