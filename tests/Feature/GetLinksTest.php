<?php

namespace Tests\Feature;

use App\Link;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;


class GetLinksTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function get_list_of_all_links()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $link = factory(Link::class, 5)->create();

        $response = $this
                ->actingAs($user, 'api')
                ->json('GET', '/api/links/all');

        $response->assertStatus(200);
        $this->assertEquals(5, count($response->original));
    }

    /** @test */
    function get_empty_list_of_all_links()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $response = $this
                ->actingAs($user, 'api')
                ->json('GET', '/api/links/all');

        $response->assertStatus(200);

        $this->assertEquals(0, count($response->json()));
    }

    /** @test */
    function get_single_link_by_link_id()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $link = factory(Link::class, 5)->create();

        $random_link_selection = rand(0,4);

        $response = $this
                ->actingAs($user, 'api')
                ->json('GET', '/api/links/all/'.$random_link_selection);

        $response->assertStatus(200);
        $link = Link::where('id', $random_link_selection);
        $this->assertEquals(1, count($link));
    }

    /** @test */
    function get_single_link_with_invalid_link_id()
    {
        //$this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $link = factory(Link::class, 5)->create();

        $random_link_selection = 6;

        $response = $this
                ->actingAs($user, 'api')
                ->json('GET', '/api/links/all/'.$random_link_selection);

        $response->assertStatus(404);

    }

}
