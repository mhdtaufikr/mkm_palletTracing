<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pallet;

class HomeController extends Controller
{
    public function index()
{
    // Fetch pallet data with status = 1
    $palletData = Pallet::where('status', 1)->orderBy('destination')->get();

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

    // Initialize data arrays for each pallet type
    $enginePieData = [];
    $transmissionPieData = [];
    $faPieData = [];

    // Loop through the counts for each pallet type and generate dataPoints
    foreach ($counts['Engine'] ?? [] as $destination => $count) {
        $enginePieData[] = [
            'y' => $count,
            'label' => $destination,
        ];
    }

    foreach ($counts['TM-Assy'] ?? [] as $destination => $count) {
        $transmissionPieData[] = [
            'y' => $count,
            'label' => $destination,
        ];
    }

    foreach ($counts['FA'] ?? [] as $destination => $count) {
        $faPieData[] = [
            'y' => $count,
            'label' => $destination,
        ];
    }

    // Pass the data to the view
    return view('home.index', [
        'enginePieData' => json_encode($enginePieData),
        'transmissionPieData' => json_encode($transmissionPieData),
        'faPieData' => json_encode($faPieData),
    ]);

}

}
