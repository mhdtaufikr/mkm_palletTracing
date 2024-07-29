<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\PalletTransactionSummary;
use Carbon\Carbon;

class SendPalletSummaryEmails extends Command
{
    protected $signature = 'emails:send-pallet-summary';
    protected $description = 'Send pallet transaction summary emails';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $oneWeekAgo = Carbon::now()->subWeek()->startOfDay();
        $today = Carbon::now()->endOfDay();

        $summaries = DB::table('pallet_transactions_summary')
                        ->whereBetween('date', [$oneWeekAgo, $today])
                        ->orderBy('no_delivery')
                        ->get();

        Mail::to('muhammad.taufik@ptmkm.co.id')->send(new PalletTransactionSummary($summaries));

        $this->info('Pallet transaction summary emails have been sent.');
    }
}
