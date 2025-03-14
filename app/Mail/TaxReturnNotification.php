<?php

// app/Mail/TaxReturnNotification.php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TaxReturnNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $employee;

    public function __construct($employee)
    {
        $this->employee = $employee;
    }

    public function build()
    {
        return $this->subject('SPT Wajib Pajak Tahun 2024')
                    ->view('emails.tax_notification')
                    ->attach($this->employee->file_path, [
                        'as' => $this->employee->nama_file,
                        'mime' => 'application/pdf',
                    ]);
    }
}

