<?php

namespace App\Http\Controllers;

use App\Reading;
use App\Sensor;
use Illuminate\Http\Request;
use Validator;

class ReadingController extends Controller
{
    /**
     * Store a single reading
     *
     * @param  Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
    	$data = $request->validate([
    		'sensor_id' => 'required',
    		'value'     => 'required'
    	]);

    	$reading = Reading::create($data);

    	return response()->json($reading, 201);
    }

    /**
     * Store multiple readings
     *
     * @param  Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function storeMultiple(Request $request)
    {
        $readings = collect();

        foreach($request->all() as $reading) {

            Validator::make($reading, [
                'sensor_id' => 'required',
                'value'     => 'required'
            ])->validate();

            $readings->push(Reading::create($reading));
        }

        return response()->json($readings, 201);
    }

    /**
     * Show a sensor readings history
     *
     * @param  Sensor $sensor
     * @return Illuminate\Http\JsonResponse
     */
    public function show(Sensor $sensor)
    {
        $readings = Reading::whereHas('sensor', function($query) use($sensor) {
            $query->where('slug', $sensor->slug);
        })->get();

        return $readings;
    }
}
