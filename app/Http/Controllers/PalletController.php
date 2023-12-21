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
    public function index(){
        // Get the current date
        $currentDate = Carbon::now()->format('Y-m-d');

        // Fetch pallet data for the current date
        /* $palletData = Pallet::whereDate('date', $currentDate)->get(); */
        $palletData = Pallet::groupBy('no_delivery')
        ->get();
        $typePallet = Dropdown::where('category','Type Pallet')->get();
        $destinationPallet = Dropdown::where('category','Destination')->get();
        return view("pallet.index", compact("palletData","typePallet","destinationPallet"));
    }



    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'date' => 'required|date',
            'no_pallet' => [
                'required',
                'string',
                'max:255',
                Rule::unique('pallets', 'no_pallet'), // Ensure no_pallet is unique in the pallets table
            ],
            'type_pallet' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
        ]);

        // Check if a pallet with the same number already exists
        if (Pallet::where('no_pallet', $request->input('no_pallet'))->exists()) {
            return redirect()->back()->with('error', 'Pallet number already exists')->withInput();
        }

        // Create a new Pallet instance and fill it with the request data
        $pallet = new Pallet([
            'date' => $request->input('date'),
            'no_pallet' => $request->input('no_pallet'),
            'type_pallet' => $request->input('type_pallet'),
            'destination' => $request->input('destination'),
        ]);

        // Save the Pallet to the database
        $pallet->save();

        // Optionally, you can return a response or redirect to another page
        return redirect()->route('pallet.index')->with('status', 'Pallet created successfully');
    }


    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'date' => 'required|date',
            'no_pallet' => 'required|string|max:255',
            'type_pallet' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
        ]);
    
        // Find the Pallet by ID
        $pallet = Pallet::findOrFail($id);
    
        // Get the original attributes
        $originalAttributes = $pallet->getOriginal();
    
        // Update the Pallet with the new data
        $pallet->update([
            'date' => $request->input('date'),
            'no_pallet' => $request->input('no_pallet'),
            'type_pallet' => $request->input('type_pallet'),
            'destination' => $request->input('destination'),
        ]);
    
        // Get the updated attributes
        $updatedAttributes = $pallet->getAttributes();
    
        // Check if there are any changes
        $changesDetected = $originalAttributes != $updatedAttributes;
    
        if ($changesDetected) {
            // Optionally, you can add additional logic or events here
    
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
