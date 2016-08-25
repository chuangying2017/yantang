<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SmsTest extends TestCase {

    use DatabaseTransactions;

    /** @test */
    public function user_can_get_validation_code_by_phone_sms()
    {
        $phone = '13242992609';

        $data = [
            'phone' => $phone,
            'reset' => 0
        ];

        $this->json('post', '/auth/sms/send-code', $data);

        $content = json_decode($this->response->getContent(), true);
        var_dump($content);

        $this->assertResponseOk();
        $this->seeJsonStructure(['success', 'message', 'type']);

        $this->seeInDatabase('laravel_sms', ['to' => $phone]);

        if ($this->app->runningUnitTests()) {
            $this->assertFalse($content['success']);
        } else {
            $this->assertTrue($content['success']);
        }

    }


}
