<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Controller;
use App\Device;
use Illuminate\Support\Facades\DB;
use PhpMqtt\Client\MQTTClient;
use PhpMqtt\Client\ConnectionSettings;

class HandleReceivedDeviceData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $mqttClient;

    public function __construct($host, $port, $username = null, $password = null, $cleanSessionFlag = true, ConnectionSettings $connectionSettings = null)
    {
        $this->mqttClient = new MQTTClient($host, $port);
        $this->mqttClient->connect($username, $password, $connectionSettings, $cleanSessionFlag);
    }

    public function __destruct()
    {
        $this->mqttClient->close();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $controllers = Controller::all();

        foreach ($controllers as $controller) {
            $topic = $controller->serial_number . "/devices/info";
            $this->mqttClient->subscribe($topic, \Closure::fromCallable([$this, 'receive']), 0);
        }

        $this->mqttClient->loop(true);

        // Register LoopEventHandler. This method executes the passed callback function ont each loop() iteration.
        // $this->mqttClient->registerLoopEventHandler(\Closure::fromCallable([$this, 'iteration']));
    }


    private function iteration($mqttc, $elapsedTime)
    {
        // Interrupt mqtt loop if timeout reached.
        if ($elapsedTime >= 10) {
            $mqttc->interrupt();
            throw new \Exception("Request timed out!", 408);
        }
    }

    private function receive($topic, $message)
    {
        echo ($topic . "\n" . $message);
        $controllerSerial = explode("/", $topic)[0];
        $controller = Controller::filter(null, $controllerSerial, null)->first();

        DB::beginTransaction();
        try {

            $controller->last_communication = date("Y-m-d H:i:s");
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
