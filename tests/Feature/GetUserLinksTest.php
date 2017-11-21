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

    /** @test */
    function get_list_of_all_user_links()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();
        $links = factory(Link::class, 5)->create();

        $user->links()->attach($links[0]);

        $response = $this
        ->actingAs($user, 'api')
        ->json('GET', '/api/links');

        $response->assertStatus(200);

        // We only associated 1 link to the user $link[0]
        $this->assertEquals(1, count($response->json()));
    }

    /** @test */
    function get_empty_list_of_all_user_links()
    {
        $this->withoutExceptionHandling();

        $user= factory(User::class)->create();
        $links = factory(Link::class, 5)->create();

        $response = $this
        ->actingAs($user, 'api')
        ->json('GET', '/api/links');

        $response->assertStatus(200);

        // We did not associate a link to the user.
        // Notice line 24 is missing from this function
        $this->assertEquals(0, count($response->json()));
    }

    /** @test */
    function get_single_link_for_user()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();
        $links = factory(Link::class, 5)->create();

        $user->links()->attach($links[0]);

        $user_link = $user->links()->get();

        $response = $this
        ->actingAs($user, 'api')
        ->json('GET', '/api/links/'.$user_link[0]->id);

        $response->assertStatus(200);
        $this->assertEquals(1, count($response->original));
    }

    /** @test */
    function get_single_link_for_user_with_invalid_id()
    {
        //$this->withoutExceptionHandling();

        $user = factory(User::class)->create();
        $links = factory(Link::class, 5)->create();

        $response = $this
        ->actingAs($user, 'api')
        ->json('GET', '/api/links/6');
        $response->assertStatus(404);
    }

}
