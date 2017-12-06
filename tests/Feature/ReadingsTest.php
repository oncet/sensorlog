<?php

namespace Tests\Feature;

use App\Reading;
use App\Sensor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReadingsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_stores_a_reading()
    {
        $reading = factory(Reading::class)->make(['value' => 25]);

        $response = $this->json('POST', '/api/readings', $reading->toArray());

        $response
            ->assertStatus(201)
            ->assertJson([
                'sensor_id' => $reading->sensor_id,
                'value'     => 25
            ]);

        $this->assertNotEmpty($response->json()['sensor_id']);
    }

    /** @test */
    public function it_stores_multiple_readings()
    {
        $readings = factory(Reading::class, 5)->make();

        $response = $this->json('POST', '/api/readings/multiple', $readings->toArray());

        $response->assertStatus(201);

        $this->assertCount(5, $response->json());
    }

    /** @test */
    public function it_returns_a_sensor_history()
    {
        $sensor = factory(Sensor::class)->create([
            'slug' => 'temperature'
        ]);

        $readings = factory(Reading::class, 10)->create([
            'sensor_id' => $sensor
        ]);

        $response = $this->json('GET', '/api/readings/temperature');

        $response->assertStatus(200);

        $this->assertCount(10, $response->json());
    }
}
