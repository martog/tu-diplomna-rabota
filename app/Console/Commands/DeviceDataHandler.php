<?php

namespace App\Console\Commands;

use App\Jobs\HandleReceivedDeviceData;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class DeviceDataHandler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'device:handle-received-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start HandleReceivedDeviceData job';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $host = Config::get('mqtt.host');
        $port = Config::get('mqtt.port');

        HandleReceivedDeviceData::dispatchNow($host, $port, 10)->onQueue("updateDevicesData");
        return 0;
    }
}
