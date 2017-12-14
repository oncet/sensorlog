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
    public function it_fails_to_stores_a_reading()
    {
        $this->failsToStoreReading('sensor_id');

        $this->failsToStoreReading('value');
    }

    /** @test */
    public function it_stores_multiple_readings()
    {
        $readings = factory(Reading::class, 5)->make();

        $response = $this->json('POST', '/api/readings/multiple', $readings->toArray());

        $response->assertStatus(201);

        $this->assertCount(5, $response->json());

        $responseReading = collect($response->json())->first();

        $this->assertArrayHasKey('id', $responseReading);
    }

    /** @test */
    public function it_fails_to_stores_multiple_readings()
    {
        $this->failsToStoreReading('sensor_id', 5);

        $this->failsToStoreReading('value', 5);
    }

    /** @test */
    public function it_returns_a_sensor_history()
    {
        $readings = factory(Reading::class, 10)->create([
            'sensor_id' => factory(Sensor::class)->create(['slug' => 'temperature'])
        ]);

        factory(Reading::class, 5)->create([
            'sensor_id' => factory(Sensor::class)->create(['slug' => 'other-sensor'])
        ]);

        $response = $this->json('GET', '/api/readings/temperature');

        $response->assertStatus(200);

        $this->assertCount(10, $response->json());

        $responseReading = collect($response->json())->first();

        $this->assertArrayHasKey('id', $responseReading);

        $this->assertEquals($readings->first()->sensor_id, $responseReading['sensor_id']);
    }

    private function failsToStoreReading($field, $amount = 1)
    {
        $suffix = $amount > 1? '/multiple' : null;

        $reading = factory(Reading::class, $amount)->make([$field => null]);

        $response = $this->json('POST', '/api/readings' . $suffix, $reading->toArray());

        $response->assertStatus(422);

        $this->assertArrayHasKey($field, $response->json()['errors']);
    }
}
