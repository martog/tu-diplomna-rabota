<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Controller extends Model
{
    protected $table = "controllers";

    public function devices()
    {
        return $this->belongsToMany("App\Device", "controller_id", "id");
    }

    public static function findByIds($ids = [])
    {
        return self::whereIn("id", $ids);
    }

    public static function findBySerialAndUserId($serial, $userId = null)
    {
        if (!$userId) {
            return self::where("serial_number", $serial);
        }

        return self::join("devices", "devices.controller_id", "=", "controllers.id")
            ->join("users", "users.id", "=", "devices.user_id")
            ->where([
                ["users.id", $userId],
                ["controllers.serial_number", $serial]
            ]);
    }
}
