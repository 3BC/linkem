<?php

namespace Tests\Feature;

use App\Link;
use App\User;
use Tests\TestCase;
use Illuminate\Session\Store;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EditLinkTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function edit_basic_link()
    {
        $this->withoutExceptionHandling();

        $link_id = 1;

        $user = factory(User::class)->create();
        $link = factory(Link::class, 5)->create();

        $input = [
          "url" => "https://www.updatedlink.com",
          "name" => "This is an updated link name",
          "description" => "This is an updated link description"
        ];

        $response = $this
        ->actingAs($user, 'api')
        ->json('PATCH', '/api/links/all/'.$link_id, $input);

        $response->assertStatus(200);

        $link = Link::where('id', $link_id)->first();

        $this->assertEquals(1, count($link));
        $this->assertEquals($input['url'], $link->url);
        $this->assertEquals($input['name'], $link->name);
        $this->assertEquals($input['description'], $link->description);
    }

    /** @test */
    public function edit_basic_link_returns_json_of_new_link()
    {
      $this->withoutExceptionHandling();

      $link_id = 1;

      $user = factory(User::class)->create();
      $link = factory(Link::class, 5)->create();

      $input = [
        "url" => "https://www.updatedlink.com",
        "name" => "This is an updated link name",
        "description" => "This is an updated link description"
      ];

      $response = $this
      ->actingAs($user, 'api')
      ->json('PATCH', '/api/links/all/'.$link_id, $input);

      $response->assertStatus(200);

      $this->assertEquals($input['url'], $response->json()['url']);
      $this->assertEquals($input['name'], $response->json()['name']);
      $this->assertEquals($input['description'], $response->json()['description']);
    }

    /** @test */
    public function edit_basic_link_requires_url_param()
    {
        //$this->withoutExceptionHandling();
        $link_id = 1;

        $user = factory(User::class)->create();
        $link = factory(Link::class, 5)->create();

        $input = [
          "url" => "",
          "name" => "link name",
          "description" => "some link description"
        ];

        $response = $this
        ->actingAs($user, 'api')
        ->json('PATCH', '/api/links/all/'.$link_id, $input);

        $response->assertStatus(422);

        $this->assertArrayHasKey('url', $response->Json()['errors']);
    }

    /** @test */
    public function edit_basic_link_requires_valid_url()
    {
      //$this->withoutExceptionHandling();

      $link_id = 1;

      $user = factory(User::class)->create();
      $link = factory(Link::class, 5)->create();

      $input = [
        "url" => "failedurl",
        "name" => "link name",
        "description" => "some link description"
      ];

      $response = $this
      ->actingAs($user, 'api')
      ->json('PATCH', '/api/links/all/'.$link_id, $input);

      $response->assertStatus(422);

      $this->assertArrayHasKey('url', $response->Json()['errors']);
    }

    /** @test */
    public function edit_basic_link_without_name()
    {
      //$this->withoutExceptionHandling();

      $link_id = 1;

      $user = factory(User::class)->create();
      $link = factory(Link::class, 5)->create();

      $input = [
        "url" => "https://www.updatedlink.com",
        "name" => "",
        "description" => "some link description"
      ];

      $response = $this
      ->actingAs($user, 'api')
      ->json('PATCH', '/api/links/all/'.$link_id, $input);

      $response->assertStatus(200);
    }

    /** @test */
    public function edit_basic_link_without_description()
    {
        $this->withoutExceptionHandling();

        $link_id = 1;

        $user = factory(User::class)->create();
        $link = factory(Link::class, 5)->create();

        $input = [
          "url" => "https://www.nodescription.com",
          "name" => "some name",
          "description" => ""
        ];

        $response = $this
        ->actingAs($user, 'api')
        ->json('PATCH', '/api/links/all/'.$link_id, $input);

        $response->assertStatus(200);

        $this->assertEquals($input['url'], $response->json()['url']);
        $this->assertEquals($input['name'], $response->json()['name']);
        $this->assertEquals($input['description'], $response->json()['description']);;
    }

}
