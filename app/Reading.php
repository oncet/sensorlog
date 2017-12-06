<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reading extends Model
{
    protected $fillable = [
    	'sensor_id',
    	'value'
    ];

    public function sensor()
    {
        return $this->belongsTo('App\Sensor');
    }
}
