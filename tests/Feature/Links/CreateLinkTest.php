<?php

namespace Tests\Feature;

use App\Link;
use App\User;
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

        $input = [
          "url" => "https://www.example.com",
          "name" => "link name",
          "description" => "some link description"
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
    }

    /** @test */
    public function create_basic_link_returns_json_of_new_link()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $input = [
          "url" => "https://www.example.com",
          "name" => "link name",
          "description" => "some link description"
        ];

        $response = $this
        ->actingAs($user, 'api')
        ->json('POST', '/api/links', $input);

        $response->assertStatus(200);

        $this->assertEquals($input['url'], $response->json()['url']);
        $this->assertEquals($input['name'], $response->json()['name']);
        $this->assertEquals($input['description'], $response->json()['description']);
    }

    /** @test */
    public function create_basic_link_requires_url_param()
    {
        $user = factory(User::class)->create();

        $input = [
          "url" => "",
          "name" => "link name",
          "description" => "some link description"
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

        $input = [
          "url" => "badurl",
          "name" => "link name",
          "description" => "some link description"
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

        $input = [
          "url" => "https://www.example.com",
          "name" => "",
          "description" => "some link description"
        ];

        $response = $this
        ->actingAs($user, 'api')
        ->json('POST', '/api/links', $input);

        $response->assertStatus(200);

        $links = Link::all();

        $this->assertEquals(1, $links->count());
        $this->assertEquals($input['url'], $links->first()->url);
        $this->assertNull($links->first()->name);
        $this->assertEquals($input['description'], $links->first()->description);
    }

    /** @test */
    public function create_basic_link_without_description()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $input = [
          "url" => "https://www.example.com",
          "name" => "some name",
          "description" => ""
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
    }

    /** @test */
    public function link_will_be_associated_with_the_user_who_submitted_it()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $input = [
          "url" => "https://www.example.com",
          "name" => "link name",
          "description" => "some link description"
        ];

        $response = $this
        ->actingAs($user, 'api')
        ->json('POST', '/api/links', $input);

        $response->assertStatus(200);

        $links = Link::with('users')->get();

        $this->assertEquals(1, $links->count());
        $this->assertEquals(1, $links->first()->users->count());
        $this->assertEquals($user->id, $links->first()->users->first()->id);
    }

    /** @test */
    public function link_with_a_duplicate_url_just_associates_link_to_user_does_not_do_anything_else()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $link = factory(Link::class)->create([
            "url" => "https://www.sameurl.com",
            "name" => "orginal name",
            "description" => "orginal description"
        ]);

        $link->users()->attach(factory(User::class)->create());

        $input = [
          "url" => "https://www.sameurl.com",
          "name" => "changed name",
          "description" => "changed description"
        ];

        $response = $this
        ->actingAs($user, 'api')
        ->json('POST', '/api/links', $input);

        $response->assertStatus(200);

        $links = Link::with('users')->get();

        $this->assertEquals(1, $links->count());
        $this->assertEquals($link->id, $links->first()->id);
        $this->assertEquals($link->url, $links->first()->url);
        $this->assertEquals($link->name, $links->first()->name);
        $this->assertEquals($link->description, $links->first()->description);

        $this->assertEquals(2, $links->first()->users()->count());
    }
}
