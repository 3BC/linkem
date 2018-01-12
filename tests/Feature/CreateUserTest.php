<?php

namespace Tests\Feature;

use App\Link;
use App\User;
use Tests\TestCase;
use Illuminate\Session\Store;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function create_basic_user()
    {
        $this->withoutExceptionHandling();

        $input = [
          "name" => "Jonathan Nigg",
          "email" => "jnigg@linkem.com",
          "password" => "password",
          "password_confirmation" => "password"
        ];

        $response = $this->json('POST', 'register', $input);

        $response->assertStatus(302);

        $user = User::where('email', $input['email']);
        $user_personal_group = $user->first()->groups()->get();
        
        $this->assertEquals(1, $user->count());
        $this->assertEquals($input['name'], $user->first()->name);
        $this->assertEquals($input['email'], $user->first()->email);

        $this->assertEquals(1, $user_personal_group->count());


    }


}
