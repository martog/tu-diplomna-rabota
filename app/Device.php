<?php

namespace App;

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
}
