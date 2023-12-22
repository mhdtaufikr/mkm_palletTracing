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

class PalletController extends Controller
{

    public function index()
    {
        // Get the current date
        $currentDate = Carbon::now()->format('Y-m-d');
    
        // Fetch pallet data grouped by no_delivery
        $palletData = Pallet::groupBy('no_delivery')->get();
    
        // Fetch all no_pallet values for each no_delivery
        $palletDetails = [];
        foreach ($palletData as $data) {
            $noDelivery = $data->no_delivery;
            $palletDetails[$noDelivery] = Pallet::where('no_delivery', $noDelivery)->pluck('no_pallet')->toArray();
        }
    
        // Fetch dropdown data
        $typePallet = Dropdown::where('category', 'Type Pallet')->get();
        $destinationPallet = Dropdown::where('category', 'Destination')->get();
    
        // Pass the data to the view
        return view("pallet.index", compact("palletData", "palletDetails", "typePallet", "destinationPallet"));
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

        // Create an array to store pallet data
        $palletsData = [];

        // Iterate through each "No. Pallet" value and create Pallet instances
        foreach ($request->input('no_pallet') as $noPallet) {
            $palletsData[] = [
                'no_delivery' => $request->input('no_delivery'),
                'date' => $request->input('date'),
                'no_pallet' => $noPallet,
                'type_pallet' => $request->input('type_pallet'),
                'destination' => $request->input('destination'),
            ];
        }

    // Insert the pallets data into the database
    Pallet::insert($palletsData);

    // Optionally, you can return a response or redirect to another page
    return redirect()->route('pallet.index')->with('status', 'Pallets created successfully');
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
    $no_delivery = $originalAttributes['no_delivery'];

    // Find all pallets with the given no_delivery
    $pallets = Pallet::where('no_delivery', $no_delivery)->get();

    // Check if any pallets were found
    if ($pallets->isEmpty()) {
        return redirect()->back()->with('failed', 'No pallets found with the specified no_delivery');
    }

    // Iterate through each pallet and update
    foreach ($pallets as $palletToUpdate) {
        // Update each pallet with the new data
        $palletToUpdate->update([
            'date' => $request->input('date'),
            'type_pallet' => $request->input('type_pallet'),
            'destination' => $request->input('destination'),
        ]);
    }

    // Optionally, you can add additional logic or events here

    return redirect()->back()->with('status', 'Pallets updated successfully');
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
        return Excel::download(new ExcelExport, 'Format Pallet Import.xlsx');
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

            return redirect()->back()->with('failed', 'Error importing Pallet. Please check the data format.');
        }
    }
    
}
