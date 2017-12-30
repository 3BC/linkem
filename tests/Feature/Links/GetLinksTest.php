<?php

namespace Tests\Feature;

use App\Link;
use App\Group;
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
                ->json('GET', '/api/links/list');

        $response->assertStatus(200);
        $this->assertEquals(5, count($response->json()));
    }

    /** @test */
    function get_list_of_links_for_user()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();
        $groups = factory(Group::class, 5)->create();

        foreach($groups as $group)
        {
          $user->groups()->attach($group->id);
          for($i=0; $i<5; $i++)
          {
              Link::create([
                'url' => "https://example-no-$i.com",
                'name' => "Example $i",
                'description' => "A description for link $i",
                'group_id' => $group->id,
                'user_id' => $user->id
              ]);
          }
        }

        $response = $this
                ->actingAs($user, 'api')
                ->json('GET', '/api/links');

        $response->assertStatus(200);
        $this->assertEquals(5, count($response->json()));
        $this->assertEquals(5, count($response->json()[0]['links']));
    }

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
        $this->assertEquals($random_link_selection, $response->Json()['id']);
    }

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
