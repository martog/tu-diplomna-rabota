<?php

namespace App\Console\Commands;

use App\Jobs\HandleReceivedDeviceData;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use App\Controller;
use App\Device;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpMqtt\Client\MQTTClient;

class DeviceDataHandler extends Command
{
    private $mqttClient;
    private $controllersTimeCheck;
    private $timeout;

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
    protected $description = 'This command uses mqtt to subscribe to all topics for receiving data from devices. When data is received, database is updated';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $host = Config::get('mqtt.host');
        $port = Config::get('mqtt.port');
        $username = Config::get('mqtt.username');
        $password = Config::get('mqtt.password');

        $this->timeout = 10;
        $this->mqttClient = new MQTTClient($host, $port);
        $this->mqttClient->connect($username, $password, null, true);
    }

    public function __destruct()
    {
        $this->mqttClient->close();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $controllers = Controller::all();

        foreach ($controllers as $controller) {
            $topic = $controller->serial_number . "/devices/info";
            $this->mqttClient->subscribe($topic, \Closure::fromCallable([$this, 'receive']), 0);
            $this->controllersTimeCheck[$controller->id] = microtime(true);
        }

        // Register LoopEventHandler. This method executes the passed callback function ont each loop() iteration.
        $this->mqttClient->registerLoopEventHandler(\Closure::fromCallable([$this, 'iteration']));
        $this->mqttClient->loop(true);
        return 0;
    }

    private function iteration($mqttc, $elapsedTime)
    {
        foreach ($this->controllersTimeCheck as $controllerId => $time) {
            if ((microtime(true) - $time) >= floatval($this->timeout)) {
                $controller = Controller::find($controllerId);
                $controller->status = "Offline";
                $controller->save();
            }
        }
    }

    private function receive($topic, $message)
    {
        echo ($topic . "\n" . $message);
        $controllerSerial = explode("/", $topic)[0];
        $controller = Controller::filter(null, $controllerSerial, null)->first();
        $this->controllersTimeCheck[$controller->id] = microtime(true);

        DB::beginTransaction();
        try {

            $controller->last_communication = date("Y-m-d H:i:s");
            $controller->status = "Online";
            $controller->save();
            $devices = $controller->devices()->get();

            $devicesData = json_decode($message, true);
            foreach ($devicesData as $deviceCode => $deviceInfo) {

                /** @var Device */
                $device = $devices->where("code", $deviceCode)
                    ->where("gpio_pin", $deviceInfo["pin"])
                    ->first();

                $device->status = $deviceInfo["status"];
                $device->save();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
