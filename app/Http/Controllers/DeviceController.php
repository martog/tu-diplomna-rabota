<?php

namespace App\Http\Controllers;

use App\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Classes\DeviceClient;


class DeviceController extends Controller
{

    private DeviceClient $deviceClient;

    public function __construct()
    {

        $host = Config::get('mqtt.host');
        $port = Config::get('mqtt.port');

        $this->deviceClient = new DeviceClient($host, $port);
    }

    public function addDevices(Request $request)
    {
        $requestData = $request->all();
        $validator = Validator::make($requestData, [
            "controller_serial" => "required|string|min:16|max:16",
            "controller_name" => "required|string"
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        $controllerSerial = $request->get("controller_serial");
        $controllerName = $request->get("controller_name");

        $requestTopic = $controllerSerial . "/devices/info/req";
        $responseTopic = $controllerSerial . "/devices/info/res";

        // Request the devices configuration for controller by $controllerSerial
        $response = $this->deviceClient->makeRequest($requestTopic, $responseTopic, "get_devices_info");

        dd($response);
    }
}
