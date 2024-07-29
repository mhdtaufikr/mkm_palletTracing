<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;

class PalletTransactionExport implements FromView
{
    protected $summaries;

    public function __construct($summaries)
    {
        $this->summaries = $summaries;
    }

    public function view(): View
    {
        return view('exports.pallet_transactions', [
            'summaries' => $this->summaries
        ]);
    }
}
