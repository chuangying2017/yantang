<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StationStaffTest extends TestCase
{

    use DatabaseTransactions;

    /** @test */
    public function it_can_get_staffs_daily_orders_lists()
    {
        $staff_id = 1;

        $this->json('get', 'stations/staffs/' .$staff_id . '/preorders', [], $this->getAuthHeader());

        $this->assertResponseOk();
    }
    
    

    /** @test */
    public function it_can_get_a_staff_info()
    {
        $user_id = 1;

        $this->json('GET', 'staffs/info',
            [],
            ['Authorization' => 'Bearer ' . $this->getToken($user_id)]
        );

        if ($this->response->getStatusCode() == 403) {
            $this->it_can_bind_user_to_staff();
            $this->json('GET', 'staffs/info',
                [],
                ['Authorization' => 'Bearer ' . $this->getToken($user_id)]
            );
        }
//        $this->dumpResponse();

        $this->assertResponseOk();
    }


    /** @test */
    public function it_can_bind_user_to_staff()
    {
        $user_id = 1;
        $token = $this->getToken($user_id);
        $staff = \App\Models\Subscribe\StationStaff::create();


        $url = 'staffs/' . $staff['id'] . '/bind';
        $response = $this->json('get', $url,
            ['bind_token' => generate_bind_token($staff['id'])],
            ['Authorization' => 'Bearer ' . $token]
        );

//        $this->dumpResponse();

        $this->assertResponseOk();

        $this->json('POST', $url,
            ['bind_token' => generate_bind_token($staff['id'])],
            ['Authorization' => 'Bearer ' . $token]
        );


        $this->assertResponseStatus(201);


        $this->json('GET', 'staffs/info', [], ['Authorization' => 'Bearer ' . $token]);

        $result = $this->getResponseData();


        $this->assertEquals($staff['id'], $result['data']['id']);
    }
}
