<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reading extends Model
{
    protected $fillable = [
    	'sensor_id',
    	'value'
    ];

    public static $rules = [
    	'sensor_id' => 'required',
    	'value'     => 'required'
    ];

    public function sensor()
    {
        return $this->belongsTo('App\Sensor');
    }

    public static function getRules()
    {
    	return self::$rules;
    }
}
