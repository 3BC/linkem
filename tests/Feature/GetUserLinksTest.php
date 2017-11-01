<?php

//namespace Tests\Feature;

use App\Link;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetUserLinksTest extends TestCase
{
    use RefreshDatabase;

    // Class var that allows me to control factory counts easily.
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
    function get_empty_list_of_all_user_links()
    {
        $this->withoutExceptionHandling();

        $u = factory(App\User::class, $this->user_count_for_factories)->create()->each(function ($u){

          // Only create link associations for odd users.
          // This way, we can use an even user to try and fetch an association where there is no link.
          if(($u->id % 2) == 1){ $u->links()->save(factory(App\Link::class)->make()); }

        });

        // Get an even user
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

    /** @test */
    function get_single_link_for_user()
    {
        $this->withoutExceptionHandling();

        $u = factory(App\User::class, $this->user_count_for_factories)->create()->each(function ($u){
          $u->links()->save(factory(App\Link::class)->make());
        });

        $random_user = rand(0,($this->user_count_for_factories-1));

        $user = $u[$random_user];

        $a_link_for_user = $user->links()->get()->first();

        $response = $this
        ->actingAs($user, 'api')
        ->json('GET', '/api/links/'.$a_link_for_user->id);

        $response->assertStatus(200);
        $this->assertEquals(1, count($response->original));
    }

    /** @test */
    function get_single_link_for_user_with_invalid_id()
    {
        $this->withoutExceptionHandling();

        $u = factory(App\User::class, $this->user_count_for_factories)->create()->each(function ($u){
          $u->links()->save(factory(App\Link::class)->make());
        });

        $random_user = rand(0,($this->user_count_for_factories-1));

        $user = $u[$random_user];

        $invalid_random_link_selection_id = $this->user_count_for_factories + 1 ;

        $response = $this
        ->actingAs($user, 'api')
        ->json('GET', '/api/links/'.$invalid_random_link_selection_id);

        //dd($response->original);

        $response->assertStatus(404);
    }

}
