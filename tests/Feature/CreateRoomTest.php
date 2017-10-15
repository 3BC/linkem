<?php

namespace Tests\Feature;

use App\Room;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateRoomTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function user_can_create_basic_room()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $input = [
            "name" => "Room name",
            "description" => "Some Room Description",
        ];

        $response = $this
                    ->actingAs($user)
                    ->json('POST', 'api/rooms', $input);

        $response->assertStatus(200);

        $rooms = Room::all();

        $this->assertEquals(1, $rooms->count());
        $this->assertEquals($input['name'], $rooms->first()->name);
        $this->assertEquals($input['description'], $rooms->first()->description);
        $this->assertEquals($user->id, $rooms->first()->owner->id);
        $this->assertEquals(0, $rooms->first()->private);
    }

    /** @test */
    public function create_basic_room_returns_json_of_new_room()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $input = [
            "name" => "Room name",
            "description" => "Some Room Description",
        ];

        $response = $this
                    ->actingAs($user)
                    ->json('POST', '/api/rooms', $input);

        $response->assertStatus(200);

        $this->assertEquals($input['name'], $response->json()['name']);
        $this->assertEquals($input['description'], $response->json()['description']);
    }

    /** @test */
    function user_must_be_logged_in_to_create_a_room()
    {
        $this->withoutExceptionHandling();

        $input = [
            "name" => "Room name",
            "description" => "Some Room Description",
        ];

        $response = $this
                    ->json('POST', '/api/rooms', $input);

        $response->assertStatus(401);

        $rooms = Room::all();

        $this->assertEquals(0, $rooms->count());
    }
}
