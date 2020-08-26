<?php

namespace App\Http\Controllers;

use App\Controller;
use App\Device;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use App\Http\Classes\DeviceClient;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use PHPUnit\Util\Json;

class DeviceController extends Controller
{

    private DeviceClient $deviceClient;

    public function __construct()
    {

        $host = Config::get('mqtt.host');
        $port = Config::get('mqtt.port');

        try {
            $this->deviceClient = new DeviceClient($host, $port);
        } catch (\Exception $e) {
            $this->deviceClient = null;
        }
    }

    public function addController(Request $request)
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

        if (Controller::filter(null, $controllerSerial, Auth::user()->id)->first()) {
            throw new \Exception("Controller already exists.");
        }

        if (!isset($this->deviceClient)) {
            return new JsonResponse("MQTTClient Error: Cannot connect to device!", 500);
        }

        $requestTopic = $controllerSerial . "/devices/info/req";
        $responseTopic = $controllerSerial . "/devices/info";

        // Request the devices configuration for controller by $controllerSerial
        $response = $this->deviceClient->makeRequest($requestTopic, $responseTopic, "get_devices_info");

        if (!isset($response["code"]) || !isset($response["message"]) || !isset($response["topic"])) {
            return new JsonResponse("Internal Server Error", 500);
        }

        if (isset($response["code"]) && $response["code"] != 200) {
            return new JsonResponse($response["message"], $response["code"]);
        }

        // Create controller with devices
        DB::beginTransaction();
        try {
            $controller = new Controller();
            $controller->name = $controllerName;
            $controller->serial_number = $controllerSerial;
            $controller->status = "Online";
            $controller->last_communication = date("Y-m-d H:i:s");
            $controller->save();

            $deviceCounter = 1;
            foreach ($response["message"] as $deviceCode => $deviceInfo) {
                $device = new Device();
                $device->code = $deviceCode;
                $device->name = "Device " . strval($deviceCounter);
                $device->gpio_pin = $deviceInfo["pin"];
                $device->status = $deviceInfo["status"];
                $device->controller_id = $controller->id;
                $device->user_id = Auth::user()->id;
                $device->save();

                $deviceCounter++;
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return new JsonResponse("Controller and devices not added! Error:" . $e->getMessage(), 500);
        }

        return new JsonResponse($controller);
    }

    public function removeController(Request $request)
    {
        $requestData = $request->all();
        $validator = Validator::make($requestData, [
            "controller_id" => "required|integer",
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        $controllerId = $request->get("controller_id");
        $user = User::find(Auth::user()->id);

        if (!Controller::find($controllerId)) {
            return new JsonResponse("Cannot find controller");
        }

        DB::beginTransaction();
        try {
            $user->devices()->where("controller_id", $controllerId)->delete();
            Controller::find($request->get("controller_id"))->delete();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return new JsonResponse("Error removing controller!");
        }

        return new JsonResponse("Success!");
    }

    public function updateController(Request $request)
    {
        $requestData = $request->all();
        $validator = Validator::make($requestData, [
            "controller_id" => "required|integer",
            "controller_serial" => "string|min:16|max:16",
            "controller_name" => "string"
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        $controllerId = $request->get("controller_id");
        $controllerSerial = $request->get("controller_serial",);
        $controllerName = $request->get("controller_name");

        $requestTopic = $controllerSerial . "/devices/info/req";
        $responseTopic = $controllerSerial . "/devices/info";

        $controller = Controller::filter($controllerId, null, Auth::user()->id)->first();
        if (!$controller) {
            throw new \Exception("Cannot find controller.");
        }

        DB::beginTransaction();
        try {

            if (isset($controllerName)) {
                $controller->name = $controllerName;
            }

            if (isset($controllerSerial)) {

                if (!isset($this->deviceClient)) {
                    return new JsonResponse("MQTTClient Error: Cannot connect to device!", 500);
                }

                // Request the devices configuration for controller by $controllerSerial
                $response = $this->deviceClient->makeRequest($requestTopic, $responseTopic, "get_devices_info");

                if (!isset($response["code"]) || !isset($response["message"]) || !isset($response["topic"])) {
                    return new JsonResponse("Internal Server Error", 500);
                }

                if (isset($response["code"]) && $response["code"] != 200) {
                    return new JsonResponse($response["message"], $response["code"]);
                }


                $controller->serial_number = $controllerSerial;
                $controller->status = "Online";
                $controller->last_communication = date("Y-m-d H:i:s");
            }
            $controller->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return new JsonResponse("Controller not updated! Error:" . $e->getMessage(), 500);
        }

        return new JsonResponse($controller);
    }

    public function setDeviceStatus(Device $device, string $status)
    {
        if ($device->user() != User::find(Auth::user()->id)) {
            return new JsonResponse("Device not found!", 404);
        }

        $validator = Validator::make(["status" => $status], [
            "status" => "string|in:On,Off"
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }

        if (!isset($this->deviceClient)) {
            return new JsonResponse("MQTTClient Error: Cannot connect to device!", 500);
        }

        $requestTopic = $device->controller()->serial_number . "/" . $device->code;
        $responseTopic = $device->controller()->serial_number . "/devices/" . $device->code . "/status";
        $response = $this->deviceClient->makeRequest($requestTopic, $responseTopic, $status);

        if (!isset($response["code"]) || !isset($response["message"]) || !isset($response["topic"])) {
            return new JsonResponse("Internal Server Error", 500);
        }

        if (isset($response["code"]) && $response["code"] != 200) {
            return new JsonResponse($response["message"], $response["code"]);
        }

        $device->status = $status;
        $device->save();

        return new JsonResponse($device, 200);
    }
}
