<?php

//namespace Tests\Feature;

use App\Link;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;


class GetLinksTest extends TestCase
{
    use RefreshDatabase;

    // Class var that allows me to control factory counts easily.
    private $link_count_for_factories = 25;

    /** @test */
    function get_list_of_all_links()
    {
        $this->withoutExceptionHandling();

<<<<<<< HEAD
        $user = factory(User::class)->create();

        $link = factory(Link::class, $this->link_count_for_factories)->create();

        $response = $this
                ->actingAs($user, 'api')
                ->json('GET', '/api/links/all');

        $response->assertStatus(200);
        $this->assertEquals($this->link_count_for_factories, count($response->original));
    }

    /** @test */
    function get_empty_list_of_all_links()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();
=======
        $u = factory(App\User::class, 25)->create()->each(function ($u){
          $u->links()->save(factory(App\Link::class)->make());
        });
>>>>>>> Link fetch tests started. Making commit before rebasing from 3BC

        $response = $this
                ->actingAs($user, 'api')
                ->json('GET', '/api/links/all');

        $response->assertStatus(200);
<<<<<<< HEAD

        $this->assertEquals(0, count($response->json()));
    }

    /** @test */
    function get_single_link_by_link_id()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $link = factory(Link::class, $this->link_count_for_factories)->create();

        $random_link_selection = rand(0,($this->link_count_for_factories-1));

        $response = $this
                ->actingAs($user, 'api')
                ->json('GET', '/api/links/all/'.$random_link_selection);

        $response->assertStatus(200);

        $this->assertEquals(1, count($response->original));
    }

    /** @test */
    function get_single_link_with_invalid_link_id()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $link = factory(Link::class, $this->link_count_for_factories)->create();

        $random_link_selection = $this->link_count_for_factories + 1 ;

        $response = $this
                ->actingAs($user, 'api')
                ->json('GET', '/api/links/all/'.$random_link_selection);

        $response->assertStatus(404);

=======
        $this->assertEquals(25, $response);
>>>>>>> Link fetch tests started. Making commit before rebasing from 3BC
    }

}
