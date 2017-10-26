<?php

namespace Tests\Feature;

use App\Room;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EditRoomTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_edit_a_rooms_name()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $room = factory(Room::class)->create([
            'name' => "Original Room Name",
            "description" => "Some Room Description",
        ]);

        $input = [
            "name" => "New Room Name",
            "description" => "Some Room Description",
        ];

        $response = $this
                    ->actingAs($user, 'api')
                    ->json('PATCH', 'api/rooms/'.$room->id, $input);

        $response->assertStatus(200);

        $rooms = Room::all();

        $this->assertEquals(1, $rooms->count());
        $this->assertEquals($input['name'], $rooms->first()->name);
        $this->assertEquals($input['description'], $rooms->first()->description);
    }

    /** @test */
    public function create_basic_room_returns_json_of_new_room()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $room = factory(Room::class)->create([
            'name' => "Original Room Name",
            "description" => "Some Room Description",
        ]);

        $input = [
            "name" => "New Room Name",
            "description" => "Some Room Description",
        ];

        $response = $this
                    ->actingAs($user, 'api')
                    ->json('PATCH', 'api/rooms/'.$room->id, $input);

        $response->assertStatus(200);

        $this->assertEquals($input['name'], $response->json()['name']);
        $this->assertEquals($input['description'], $response->json()['description']);
    }

    /** @test */
    public function user_must_be_logged_in_to_edit_a_room()
    {
        $room = factory(Room::class)->create([
            'name' => "Original Room Name",
            "description" => "Some Room Description",
        ]);

        $input = [
            "name" => "New Room Name",
            "description" => "Some Room Description",
        ];

        $response = $this
                    ->json('PATCH', 'api/rooms/'.$room->id, $input);

        $response->assertStatus(401);
    }

    /** @test */
    public function room_must_have_a_name_when_editing()
    {
        // $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $room = factory(Room::class)->create([
            'name' => "Original Room Name",
            "description" => "Some Room Description",
        ]);

        $input = [
            "name" => "",
            "description" => "Some Room Description",
        ];

        $response = $this
                    ->actingAs($user, 'api')
                    ->json('PATCH', 'api/rooms/'.$room->id, $input);

        $response->assertStatus(422);

        $this->assertArrayHasKey('name', $response->Json()['errors']);
    }

    /** @test */
    public function room_must_have_a_unique_name_when_editing()
    {
        // $this->withoutExceptionHandling();

        $roomOne = factory(Room::class)->create([
            "name" => "Room Name"
        ]);

        $roomTwo = factory(Room::class)->create([
            "name" => "Duplicate Name"
        ]);

        $user = factory(User::class)->create();

        $input = [
            "name" => "Duplicate Name",
        ];

        $response = $this
                    ->actingAs($user, 'api')
                    ->json('PATCH', 'api/rooms/'.$roomOne->id, $input);

        $response->assertStatus(422);
        $this->assertArrayHasKey('name', $response->Json()['errors']);
    }

    /** @test */
    public function room_can_be_updated_with_same_information()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $room = factory(Room::class)->create([
            'name' => "Original Room Name",
            "description" => "Some Room Description",
        ]);

        $input = [
            "name" => "Original Room Name",
            "description" => "Some Room Description",
        ];

        $response = $this
                    ->actingAs($user, 'api')
                    ->json('PATCH', 'api/rooms/'.$room->id, $input);

        $response->assertStatus(200);

        $this->assertEquals($input['name'], $response->json()['name']);
        $this->assertEquals($input['description'], $response->json()['description']);
    }
}
