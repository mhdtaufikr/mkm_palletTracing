<?php

// AssetExport.php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExcelExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // Return an empty collection, as we're only defining headers
        return collect();
    }

    public function headings(): array
    {
        return  [
            'No. Delivery', 'No Pallet', 'Type Pallet', 'Destination', 'Date'
        ];
    }
}
