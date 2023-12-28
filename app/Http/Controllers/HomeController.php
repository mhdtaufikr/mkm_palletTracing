<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pallet;

class HomeController extends Controller
{
    public function index()
    {
        // Fetch pallet data with status = 1
        $palletData = Pallet::where('status', 1)->get();

        // Initialize counts array
        $counts = [];

        // Loop through the pallet data
        foreach ($palletData as $pallet) {
            $typePallet = $pallet->type_pallet;
            $destination = $pallet->destination;

            if (!isset($counts[$typePallet])) {
                $counts[$typePallet] = [];
            }

            if (!isset($counts[$typePallet][$destination])) {
                $counts[$typePallet][$destination] = 1;
            } else {
                $counts[$typePallet][$destination]++;
            }
        }

        // Pass the data to the view
        return view('home.index', [
            'enginePieData' => json_encode($counts['Engine'] ?? []),
            'transmissionPieData' => json_encode($counts['TM-Assy'] ?? []),
            'faPieData' => json_encode($counts['FA'] ?? []),
        ]);

    }
}
