<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PalletTransactionSummary extends Mailable
{
    use Queueable, SerializesModels;

    public $summaries;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($summaries)
    {
        $this->summaries = $summaries;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.pallet_transaction_summary')
                    ->with(['summaries' => $this->summaries]);
    }
}
