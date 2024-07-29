<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PalletTransactionExport;

class PalletTransactionSummary extends Mailable
{
    use Queueable, SerializesModels;

    public $summaries;

    public function __construct($summaries)
    {
        $this->summaries = $summaries;
    }

    public function build()
    {
        $excelFile = Excel::raw(new PalletTransactionExport($this->summaries), \Maatwebsite\Excel\Excel::XLSX);

        return $this->view('emails.pallet_transaction_summary')
                    ->with(['summaries' => $this->summaries])
                    ->attachData($excelFile, 'pallet_transactions.xlsx', [
                        'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    ]);
    }
}
