<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StationAssignTest extends TestCase {

    /** @test */
    public function it_can_check_the_location_is_in_station_service_area()
    {
        $geo = [
            [23.161885, 113.330456],
            [23.160385, 113.331616],
            [23.160403, 113.333071],
            [23.159571, 113.333680],
            [23.158541, 113.336158],
            [23.157619, 113.336472],
            [23.155486, 113.334585],
            [23.155721, 113.330613],
            [23.158830, 113.326858],
        ];



        $assign = new  \App\Services\Preorder\PreorderAssignService(new \App\Repositories\Station\EloquentStationRepository());

        // 中国广东省广州市天河区红英小学
        $inside = [23.157195, 113.330319];
        $result = $assign->inSide($inside[0], $inside[1], $geo);
        $this->assertTrue($result);

        $out_side = [23.159711, 113.333818];
        $result = $assign->inSide($out_side[0], $out_side[1], $geo);
        $this->assertFalse($result);

    }
}
