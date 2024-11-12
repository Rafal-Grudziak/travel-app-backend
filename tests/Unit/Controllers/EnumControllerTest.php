<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\EnumController;
use App\Http\Resources\EnumResource;
use App\Models\TravelPreference;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class EnumControllerTest extends TestCase
{

    public function test_get_travel_preferences()
    {
        $travelPreference = $this->mock(TravelPreference::class);
        $travelPreference->shouldReceive('all')
            ->once()
            ->andReturn(collect());

        $enumResource = $this->mock(EnumResource::class);
        $enumResource->shouldReceive('collection')
            ->once()
            ->andReturn([]);


        $controller = new EnumController();

        $response = $controller->getTravelPreferences();

        $this->assertEquals(200, $response->status());

    }
}
