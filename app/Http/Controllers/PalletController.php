<?php

namespace App\Http\Controllers;

use App\Models\Dropdown;
use Illuminate\Http\Request;
use App\Models\Pallet;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use App\Exports\ExcelExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PalletImport;
use Illuminate\Support\Facades\DB;
use Throwable;
use App\Exports\PalletExport;
class PalletController extends Controller
{

    public function index()
    {
       
        // Fetch pallet data grouped by no_pallet
        $palletData = Pallet::orderBy('created_at', 'desc')->where('status','1')->get();
        // Fetch dropdown data
        $typePallet = Dropdown::where('category', 'Type Pallet')->get();
        $destinationPallet = Dropdown::where('category', 'Destination')->get();

        // Pass the data to the view
        return view("pallet.index", compact("palletData", "typePallet", "destinationPallet"));
    }


    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'no_delivery' => 'required|string|max:255',
            'date' => 'required|date',
            'no_pallet' => 'required|array',
            'no_pallet.*' => [
                'string',
                'max:255',
                Rule::unique('pallets', 'no_pallet'), // Ensure no_pallet is unique in the pallets table
            ],
            'type_pallet' => 'required|string|max:255',
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
                return redirect()->back()->with('error', 'No delivery already exists')->withInput();
            }

            // Create an array to store pallet data
            $palletsData = [];

            // Iterate through each "No. Pallet" value and create/update Pallet instances
            foreach ($request->input('no_pallet') as $noPallet) {
                // Check for destination validation
                $validDestination = in_array($request->input('destination'), ['TJU', 'KRM']);
                $validMoveDestination = in_array($request->input('destination'), ['MKM']) && in_array($request->input('destination'), ['MKM']);

                if (!$validDestination || !$validMoveDestination) {
                    // Destination validation failed, rollback the transaction
                    DB::rollBack();
                    return redirect()->back()->with('error', 'Invalid destination')->withInput();
                }

                // Check if a record with the same no_pallet and destination exists
                $existingPallet = Pallet::where('no_pallet', $noPallet)
                    ->where('destination', $request->input('destination'))
                    ->where('status', 1)
                    ->first();

                if ($existingPallet) {
                    // Rollback the transaction and return with an error message
                    DB::rollBack();
                    return redirect()->back()->with('error', 'Pallet with the same destination already exists')->withInput();
                }

                // Check if a record with the same no_pallet exists
                $existingPallet = Pallet::where('no_pallet', $noPallet)->where('status', 1)->first();

                if ($existingPallet) {
                    // Update the existing record's status to 0
                    $existingPallet->update(['status' => 0]);
                }

                // Create a new Pallet with status 1
                $palletsData[] = [
                    'no_delivery' => $request->input('no_delivery'),
                    'date' => $request->input('date'),
                    'no_pallet' => $noPallet,
                    'type_pallet' => $request->input('type_pallet'),
                    'destination' => $request->input('destination'),
                    'status' => 1,
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
            return redirect()->back()->with('error', 'An error occurred while saving the data')->withInput();
        }
    }


    

    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'date' => 'required|date_format:Y-m-d',
            'type_pallet' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
        ]);
    
        // Find the Pallet by ID
        $pallet = Pallet::findOrFail($id);
    
        // Get the original attributes
        $originalAttributes = $pallet->getOriginal();
    
        // Update the Pallet with the new data
        $pallet->date = $request->input('date');
        $pallet->type_pallet = $request->input('type_pallet');
        $pallet->destination = $request->input('destination');
    
        // Check if any attributes are dirty
        if ($pallet->isDirty()) {
            // Optionally, you can add additional logic or events here
    
            // Save the Pallet only if there are changes
            $pallet->save();
    
            return redirect()->back()->with('status', 'Pallet updated successfully');
        } else {
            return redirect()->back()->with('failed', 'No changes made to the Pallet');
        }
    }
    
        
    public function delete($id)
    {
        // Find the Pallet by ID
        $pallet = Pallet::findOrFail($id);
    
        // Delete the Pallet
        $pallet->delete();
    
        // Optionally, you can add additional logic or events here
    
        return redirect()->back()->with('status', 'Pallet deleted successfully');
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

            return redirect()->back()->with('status', 'Pallets imported successfully');
        } catch (Throwable $e) {
            // If an error occurs, rollback the transaction
            DB::rollBack();

            // Log or handle the error as needed
            // You can also use $e->getMessage() to get the error message
            $errorMessage = $e->getMessage();

            if ($errorMessage) {
                // If there's a specific error message, display it
                return redirect()->back()->with('failed', $errorMessage);
            }
            return redirect()->back()->with('failed', 'Error importing Pallet. Please check the data format.');
        }
    }

    public function palletSearch(Request $request)
    {
        // Validate the request data
        $request->validate([
            'searchBy' => 'required|in:no_pallet,date',
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
                $query->where('no_pallet', $palletNo);
            }
        } elseif ($searchBy === 'date' && $dateFrom && $dateTo) {
            $query->whereBetween('date', [$dateFrom, $dateTo]);
        }

        // Add any additional conditions as needed

        // Execute the query
        $palletData = $query->get();

        // Do something with the results
        return view("pallet.index", compact("palletData", "typePallet", "destinationPallet"));
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new PalletExport, 'pallet_data.xlsx');
    }
    
    
}
