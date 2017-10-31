<?php

namespace Tests\Feature;

use App\Link;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetLinksTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function get_list_of_all_links()
    {
        $this->withoutExceptionHandling();

        $link = factory(Link::class, 5)->create();

        $response = $this->json('GET', '/api/links');

        $response->assertStatus(200);

        $this->assertEquals(5, count($response->json()));
    }

}
