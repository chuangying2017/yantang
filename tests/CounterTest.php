<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CounterTest extends TestCase
{
    /** @test */
    public function it_can_get_a_counter()
    {
        $station = \App\Models\Subscribe\Station::find(4);

        $station->load('counter');
    }
}
