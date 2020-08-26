<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Controller extends Model
{
    protected $table = "controllers";

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
}
