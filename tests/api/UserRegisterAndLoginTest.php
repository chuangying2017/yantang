<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserRegisterAndLoginTest extends TestCase {

    use DatabaseTransactions;

    protected $phone;
    protected $password;
    protected $code;

    protected function setUp()
    {
        parent::setUp();
        $this->phone = '13242992609';
        $this->password = '1234567';
        $this->code = null;
    }


    /** @test */
    public function user_can_register()
    {

        $this->user_can_get_validation_code_by_phone_sms();

        $register_data = [
            'phone' => $this->phone,
            'password' => $this->password,
            'password_confirmation' => $this->password,
            'code' => $this->code,
        ];

        $this->json('post', 'auth/register', $register_data);

        $this->dumpResponse();

        $this->assertResponseOk();
        $this->seeJsonStructure(['data' => ['token', 'roles' => []]]);

        $content = $this->getResponseData();
        $user = \JWTAuth::toUser(array_get($content, 'data.token'));

        $this->seeInDatabase('wallet', ['user_id' => $user['id']]);
        $this->seeInDatabase('clients', ['user_id' => $user['id']]);
    }

    /** @test */
    public function user_can_login_by_phone_and_password()
    {

        $this->user_can_register();

        $login_data = [
            'phone' => $this->phone,
            'password' => $this->password
        ];

        $this->json('post', 'auth/login', $login_data);

        $this->dumpResponse();

        $this->assertResponseOk();
        $this->seeJsonStructure(['data' => ['token', 'roles' => []]]);
    }


    private function user_can_get_validation_code_by_phone_sms()
    {
        $phone = $this->phone;

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

        $send_data = \DB::table('laravel_sms')->where('to', $phone)->orderBy('created_at', 'desc')->pluck('data');
        $data = json_decode($send_data[0], true);
        $this->code = $data['code'];
    }

    /** @test */
    public function it_can_create_client_and_wallet_for_user_when_register()
    {
        #todo
    }

}
