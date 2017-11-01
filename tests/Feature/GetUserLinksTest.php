<?php

//namespace Tests\Feature;

use App\Link;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetUserLinksTest extends TestCase
{
    use RefreshDatabase;
    private $user_count_for_factories = 25;

    /** @test */
    function get_list_of_all_user_links()
    {
        $this->withoutExceptionHandling();

        $u = factory(App\User::class, $this->user_count_for_factories)->create()->each(function ($u){
          $u->links()->save(factory(App\Link::class)->make());
        });

        $random_user = rand(0,($this->user_count_for_factories-1));

        $user = $u[$random_user];

        $response = $this
        ->actingAs($user, 'api')
        ->json('GET', '/api/links');

        $response->assertStatus(200);
        $this->assertEquals(1, count($response->original));
    }

    /** @test */
    function get_list_of_all_user_links_in_json()
    {
        $this->withoutExceptionHandling();

        $u = factory(App\User::class, $this->user_count_for_factories)->create()->each(function ($u){
          $u->links()->save(factory(App\Link::class)->make());
        });

        $random_user = rand(0,($this->user_count_for_factories-1));

        $user = $u[$random_user];

        $response = $this
        ->actingAs($user, 'api')
        ->json('GET', '/api/links');

        $response->assertStatus(200);

        $this->assertEquals(1, count($response->json()));
    }

    /** @test */
    function get_empty_list_of_all_user_links()
    {
        $this->withoutExceptionHandling();

        $u = factory(App\User::class, $this->user_count_for_factories)->create()->each(function ($u){

          // Only create link associations for odd users.
          if(($u->id % 2) == 1){ $u->links()->save(factory(App\Link::class)->make()); }

        });

        do{
          $random_user = rand(0,($this->user_count_for_factories-1));
        }while($random_user % 2 == 0);

        $user = $u[$random_user];

        $response = $this
        ->actingAs($user, 'api')
        ->json('GET', '/api/links');

        $response->assertStatus(200);
        $this->assertEquals(0, count($response->original));
    }

}
