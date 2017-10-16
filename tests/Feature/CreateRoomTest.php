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
                    ->actingAs($user, 'api')
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
                    ->actingAs($user, 'api')
                    ->json('POST', '/api/rooms', $input);

        $response->assertStatus(200);

        $this->assertEquals($input['name'], $response->json()['name']);
        $this->assertEquals($input['description'], $response->json()['description']);
    }

    /** @test */
    function user_must_be_logged_in_to_create_a_room()
    {
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

    /** @test */
    function room_must_have_a_name()
    {
        // $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $input = [
            "name" => "",
            "description" => "Some Room Description",
        ];

        $response = $this
                    ->actingAs($user, 'api')
                    ->json('POST', '/api/rooms', $input);

        $response->assertStatus(422);

        $rooms = Room::all();
        $this->assertEquals(0, $rooms->count());
        $this->assertArrayHasKey('name', $response->Json()['errors']);
    }

    /** @test */
    function room_must_have_a_unique_name()
    {
        // $this->withoutExceptionHandling();

        $room = factory(Room::class)->create([
            "name" => "Duplicate Name"
        ]);

        $user = factory(User::class)->create();

        $input = [
            "name" => "Duplicate Name",
        ];

        $response = $this
                    ->actingAs($user, 'api')
                    ->json('POST', '/api/rooms', $input);

        $response->assertStatus(422);

        $rooms = Room::all();
        $this->assertEquals(1, $rooms->count());
        $this->assertArrayHasKey('name', $response->Json()['errors']);
    }

    /** @test */
    function user_is_added_as_moderator_to_group_they_create()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $input = [
            "name" => "Room name",
            "description" => "Some Room Description",
        ];

        $response = $this
                    ->actingAs($user, 'api')
                    ->json('POST', 'api/rooms', $input);

        $response->assertStatus(200);

        $rooms = Room::all();

        $this->assertEquals(1, $rooms->first()->moderators()->count());
        $this->assertEquals($user->id, $rooms->first()->moderators()->first()->id);
    }

    /** @test */
    function user_is_added_as_follower_to_group_they_create()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $input = [
            "name" => "Room name",
            "description" => "Some Room Description",
        ];

        $response = $this
                    ->actingAs($user, 'api')
                    ->json('POST', 'api/rooms', $input);

        $response->assertStatus(200);

        $rooms = Room::all();

        $this->assertEquals(1, $rooms->first()->followers()->count());
        $this->assertEquals($user->id, $rooms->first()->followers()->first()->id);
    }
}
