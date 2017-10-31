<?php

//namespace Tests\Feature;

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

        $u = factory(App\User::class, 25)->create()->each(function ($u){
          $u->links()->save(factory(App\Link::class)->make());
        });

        $response = $this->json('GET', '/api/links');
        //dd($response);
        $response->assertStatus(200);
        $this->assertEquals(25, $response);
    }

}
