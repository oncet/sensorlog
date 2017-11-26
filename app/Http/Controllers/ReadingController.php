<?php

namespace App\Http\Controllers;

use Validator;
use App\Reading;
use Illuminate\Http\Request;

class ReadingController extends Controller
{
    public function store(Request $request)
    {
    	$data = $request->validate([
    		'sensor_id' => 'required',
    		'value'     => 'required'
    	]);

    	$reading = Reading::create($data);

    	return response()->json($reading, 201);
    }

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
}
