<?php

namespace Tests\Feature;

use App\Link;
use App\User;
use App\Group;
use Tests\TestCase;
use Illuminate\Session\Store;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateLinkTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function create_basic_link()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();
        $group = factory(Group::class)->create();

        $group->contributors()->attach($user->id);

        $input = [
          "url" => "https://www.example.com",
          "name" => "link name",
          "description" => "some link description",
          "group_id" => $group->id,
        ];

        $response = $this
        ->actingAs($user, 'api')
        ->json('POST', '/api/links', $input);

        $response->assertStatus(200);

        $links = Link::all();

        $this->assertEquals(1, $links->count());
        $this->assertEquals($input['url'], $links->first()->url);
        $this->assertEquals($input['name'], $links->first()->name);
        $this->assertEquals($input['description'], $links->first()->description);
        $this->assertEquals($input['group_id'], $links->first()->group_id);
        $this->assertEquals($user->id, $links->first()->user_id);
    }

    /** @test */
    public function create_basic_link_returns_json_of_new_link()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();
        $group = factory(Group::class)->create();

        $group->contributors()->attach($user->id);

        $input = [
          "url" => "https://www.example.com",
          "name" => "link name",
          "description" => "some link description",
          "group_id" => $group->id,
        ];

        $response = $this
        ->actingAs($user, 'api')
        ->json('POST', '/api/links', $input);

        $response->assertStatus(200);

        $this->assertEquals($input['url'], $response->json()['url']);
        $this->assertEquals($input['name'], $response->json()['name']);
        $this->assertEquals($input['description'], $response->json()['description']);
        $this->assertEquals($input['group_id'], $response->json()['group_id']);
        $this->assertEquals($user->id, $response->json()['user_id']);
    }

    /** @test */
    public function create_basic_link_requires_user_to_be_logged_in()
    {
        $user = factory(User::class)->create();
        $group = factory(Group::class)->create();

        $group->contributors()->attach($user->id);

        $input = [
          "url" => "https://example.com",
          "name" => "link name",
          "description" => "some link description",
          "group_id" => $group->id,
        ];

        $response = $this
        ->json('POST', '/api/links', $input);

        $response->assertStatus(401);

        $links = Link::all();
        $this->assertEquals(0, $links->count());
    }

    /** @test */
    public function create_basic_link_requires_url_param()
    {
        $user = factory(User::class)->create();
        $group = factory(Group::class)->create();

        $group->contributors()->attach($user->id);

        $input = [
          "url" => "",
          "name" => "link name",
          "description" => "some link description",
          "group_id" => $group->id,
        ];

        $response = $this
        ->actingAs($user, 'api')
        ->json('POST', '/api/links', $input);

        $response->assertStatus(422);

        $links = Link::all();
        $this->assertEquals(0, $links->count());
        $this->assertArrayHasKey('url', $response->Json()['errors']);
    }

    /** @test */
    public function create_basic_link_requires_valid_url()
    {
        //$this->withoutExceptionHandling();

        $user = factory(User::class)->create();
        $group = factory(Group::class)->create();

        $group->contributors()->attach($user->id);

        $input = [
          "url" => "badurl",
          "name" => "link name",
          "description" => "some link description",
          "group_id" => $group->id
        ];

        $response = $this
        ->actingAs($user, 'api')
        ->json('POST', '/api/links', $input);

        $response->assertStatus(422);

        $links = Link::all();
        $this->assertEquals(0, $links->count());
        $this->assertArrayHasKey('url', $response->Json()['errors']);
    }

    /** @test */
    public function create_basic_link_without_name()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();
        $group = factory(Group::class)->create();

        $group->contributors()->attach($user->id);

        $input = [
          "url" => "https://www.example.com",
          "name" => "",
          "description" => "some link description",
          "group_id" => $group->id

        ];

        $response = $this
        ->actingAs($user, 'api')
        ->json('POST', '/api/links', $input);

        $response->assertStatus(200);
    }

    /** @test */
    public function create_basic_link_without_description()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();
        $group = factory(Group::class)->create();

        $group->contributors()->attach($user->id);

        $input = [
          "url" => "https://www.example.com",
          "name" => "some name",
          "description" => "",
          "group_id" => $group->id
        ];

        $response = $this
        ->actingAs($user, 'api')
        ->json('POST', '/api/links', $input);

        $response->assertStatus(200);

        $links = Link::all();

        $this->assertEquals(1, $links->count());
        $this->assertEquals($input['url'], $links->first()->url);
        $this->assertEquals($input['name'], $links->first()->name);
        $this->assertNull($links->first()->description);
        $this->assertEquals($input['group_id'], $links->first()->group_id);
    }

    /** @test */
    public function link_will_be_associated_with_the_user_who_submitted_it()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();
        $group = factory(Group::class)->create();

        $group->contributors()->attach($user->id);

        $input = [
          "url" => "https://www.example.com",
          "name" => "link name",
          "description" => "some link description",
          "group_id" => $group->id
        ];

        $response = $this
        ->actingAs($user, 'api')
        ->json('POST', '/api/links', $input);

        $response->assertStatus(200);

        $links = Link::all();

        $this->assertEquals(1, $links->count());
        $this->assertEquals($user->id, $links->first()->user_id);
    }

}
