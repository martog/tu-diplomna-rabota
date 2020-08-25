<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $table = "devices";


    public function controller()
    {
        $this->hasOne('App\Controller', 'id', 'controller_id');
    }
}
