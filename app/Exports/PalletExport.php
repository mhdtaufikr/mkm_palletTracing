<?php
// app/Exports/PalletExport.php

namespace App\Exports;

use App\Models\Pallet;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PalletExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Pallet::all();
    }

    public function headings(): array
    {
        return [
            'No',
            'No. Delivery',
            'No. Pallet',
            'Type Pallet',
            'Destination',
            'Date',
            'Status',
            'Created At',
            'Updated At',
        ];
    }
}

