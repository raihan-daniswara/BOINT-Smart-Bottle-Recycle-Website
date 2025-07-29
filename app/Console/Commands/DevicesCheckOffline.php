<?php

namespace App\Console\Commands;

use App\Models\Device;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DevicesCheckOffline extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'devices:check-offline';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Set device status to offline if status is online and last_active more than 2 minutes ago';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = now()->timestamp;

        $devices = Device::where('status', 1)->get();

        foreach ($devices as $device) {
            // last_active > 125 sec
            if ($now - $device->last_active > 125) {
                $device->status = 0;
                $device->save();

                Log::info("Device {$device->name} dinyatakan offline otomatis.");
            }
        }

        $this->info("meriksaan selesai. Jumlah device diproses: " . $devices->count());
        return Command::SUCCESS;
    }
}