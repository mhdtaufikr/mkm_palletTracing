<?php

// ExcelExport.php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExcelExport implements FromCollection, WithHeadings
{
    protected $note;

    public function __construct($note)
    {
        $this->note = $note;
    }

    public function collection()
    {
        // Return an empty collection, as we're only defining headers
        return collect();
    }

    public function headings(): array
    {
        return [
            'No. Delivery', 'No Pallet', 'Type Pallet', 'Destination', 'Date'
        ];
    }

    public function export(): Xlsx
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $sheet->fromArray([$this->headings()], null, 'A1');
        
        // Add note below "Date" heading
        $sheet->setCellValue('E2', $this->note);

        return new Xlsx($spreadsheet);
    }
}
