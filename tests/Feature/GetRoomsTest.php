<?php

namespace Tests\Feature;

use App\Room;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetRoomsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_list_of_all_rooms_the_user_owns()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $rooms = factory(Room::class, 5)->create();

        $user_rooms = factory(Room::class, 2)->create([
            "owner_id" => $user->id
        ]);

        $response = $this
                    ->actingAs($user, 'api')
                    ->json('GET', '/api/rooms');

        $response->assertStatus(200);

        $this->assertEquals(2, count($response->json()));

        $response->assertJson($user_rooms->toArray());
    }

    /** @test */
    public function user_must_be_logged_in_to_get_list_of_rooms_they_have()
    {
        $response = $this
                    ->json('GET', '/api/rooms');

        $response->assertStatus(401);
    }
}
