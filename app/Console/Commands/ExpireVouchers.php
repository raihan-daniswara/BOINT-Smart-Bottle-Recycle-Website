<?php

namespace App\Console\Commands;

use App\Models\Redeem;
use Illuminate\Console\Command;

class ExpireVouchers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:expire-vouchers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /** 
     * Execute the console command.
     */
    public function handle()
    {
        $expired = Redeem::where('status', 'active')
        ->where('expires_at', '<', now())
        ->update(['status' => 'expired']);

        $this->info("$expired vouchers expired.");
    }
}
