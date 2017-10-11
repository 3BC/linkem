<?php

namespace Tests\Feature;

use App\Link;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateLinkTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function create_basic_link()
    {
        $this->withoutExceptionHandling();

        $input = [
          "url" => "www.example.com",
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
    public function create_basic_link_requires_url()
    {
        $this->withoutExceptionHandling();

        $input = [
          "url" => "",
          "name" => "link name",
          "description" => "some link description"
        ];

        $response = $this->json('POST', '/api/links', $input);

        $response->assertStatus(422);

        $links = Link::all();

        $this->assertEquals(0, $links->count());
        $this->assertArrayHasKey('url', $response->decodeResponseJson());
    }
}
