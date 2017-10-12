<?php

namespace Tests\Feature;

use App\Link;
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

        $input = [
          "url" => "https://www.example.com",
          "name" => "link name",
          "description" => "some link description"
        ];

        $response = $this->json('POST', '/api/links', $input);

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

        $input = [
          "url" => "https://www.example.com",
          "name" => "link name",
          "description" => "some link description"
        ];

        $response = $this->json('POST', '/api/links', $input);

        $response->assertStatus(200);

        $this->assertEquals($input['url'], $response->json()['url']);
        $this->assertEquals($input['name'], $response->json()['name']);
        $this->assertEquals($input['description'], $response->json()['description']);
    }

    /** @test */
    public function create_basic_link_requires_url_param()
    {
        $input = [
          "url" => "",
          "name" => "link name",
          "description" => "some link description"
        ];

        $response = $this->json('POST', '/api/links', $input);

        $response->assertStatus(422);

        $links = Link::all();
        $this->assertEquals(0, $links->count());
        $this->assertArrayHasKey('url', $response->Json()['errors']);
    }

    /** @test */
    public function create_basic_link_requires_valid_url()
    {
        //$this->withoutExceptionHandling();

        $input = [
          "url" => "badurl",
          "name" => "link name",
          "description" => "some link description"
        ];

        $response = $this->json('POST', '/api/links', $input);

        $response->assertStatus(422);

        $links = Link::all();
        $this->assertEquals(0, $links->count());
        $this->assertArrayHasKey('url', $response->Json()['errors']);
    }

    /** @test */
    public function create_basic_link_without_name()
    {
        $this->withoutExceptionHandling();

        $input = [
          "url" => "https://www.example.com",
          "name" => "",
          "description" => "some link description"
        ];

        $response = $this->json('POST', '/api/links', $input);

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

        $input = [
          "url" => "https://www.example.com",
          "name" => "some name",
          "description" => ""
        ];

        $response = $this->json('POST', '/api/links', $input);

        $response->assertStatus(200);

        $links = Link::all();

        $this->assertEquals(1, $links->count());
        $this->assertEquals($input['url'], $links->first()->url);
        $this->assertEquals($input['name'], $links->first()->name);
        $this->assertNull($links->first()->description);
    }
}
