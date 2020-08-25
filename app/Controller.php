<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Controller extends Model
{
    protected $table = "controllers";

    public function devices()
    {
        return $this->belongsToMany('App\Device', 'controller_id', 'id');
    }

    public static function findByIds($ids = [])
    {
        return self::whereIn("id", $ids);
    }
}
