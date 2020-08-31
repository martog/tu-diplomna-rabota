<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Controller extends Model
{
    protected $table = "controllers";
    protected $hidden = ["laravel_through_key", "serial_number"];

    public function devices()
    {
        return $this->hasMany("App\Device", "controller_id", "id");
    }

    public static function findByIds($ids = [])
    {
        return self::whereIn("id", $ids);
    }

    public static function filter($id = null, $serial = null, $userId = null)
    {
        $query = self::join("devices", "devices.controller_id", "=", "controllers.id")
            ->join("users", "users.id", "=", "devices.user_id");

        if (isset($id)) {
            $query->where("controllers.id", $id);
        }

        if (isset($serial)) {
            $query->where("controllers.serial_number", $serial);
        }

        if (isset($userId)) {
            $query->where("users.id", $userId);
        }

        return $query->select("controllers.*");
    }

    public static function getControllersWithDevicesStatusByUser($userId)
    {
        $controllerDevices = self::select(
            "controllers.id",
            "controllers.name",
            "controllers.status",
            "controllers.last_communication",
            "devices.status as device_status",
            DB::raw("case when devices.status = 'On' then count(devices.id) else 0 end as devices_num_on"),
            DB::raw("case when devices.status = 'Off' then count(devices.id) else 0 end as devices_num_off")
        )
            ->join("devices", "devices.controller_id", "=", "controllers.id")
            ->where("devices.user_id", $userId)
            ->groupBy("controllers.id", "devices.status");

        $query = self::withExpression('controllerDevices', $controllerDevices)
            ->select(
                "id",
                "name",
                "status",
                "last_communication",
                DB::raw("
                    json_build_object(
                        'On', sum(devices_num_on),
                        'Off', sum(devices_num_off)
                    ) as devices")
            )
            ->from("controllerDevices")
            ->groupBy("id", "name", "status", "last_communication")
            ->orderBy("name");

        return $query->get();
    }
}
