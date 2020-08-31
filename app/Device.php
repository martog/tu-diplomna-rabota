<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $table = "devices";
    protected $hidden = ["code"];


    public function controller()
    {
        return $this->belongsTo('App\Controller')->first();
    }

    public function user()
    {
        return $this->belongsTo('App\User')->first();
    }

    public static function getByControllerId($controllerId)
    {
        return self::select(
            "devices.id",
            "devices.name",
            "devices.status",
            "devices.updated_at as last_updated",
            "controllers.id as controller_id",
            "controllers.name as controller_name"
        )
            ->join("controllers", "devices.controller_id", "=", "controllers.id")
            ->where("controllers.id", $controllerId);
    }

    public function getCreatedAtAttribute($date)
    {
        return (new Carbon($date))->toDateTimeString();
    }

    public function getUpdatedAtAttribute($date)
    {
        return (new Carbon($date))->toDateTimeString();
    }
}
