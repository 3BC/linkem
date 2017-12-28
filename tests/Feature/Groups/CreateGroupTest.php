<?php

namespace Tests\Feature;

use App\Group;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateGroupTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function user_can_create_basic_group()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $input = [
            "name" => "Group name",
            "description" => "Some Group Description",
        ];

        $response = $this
                    ->actingAs($user, 'api')
                    ->json('POST', 'api/groups', $input);

        $response->assertStatus(200);

        $groups = Group::all();

        $this->assertEquals(1, $groups->count());
        $this->assertEquals($input['name'], $groups->first()->name);
        $this->assertEquals($input['description'], $groups->first()->description);
        $this->assertEquals(0, $groups->first()->private);
    }

    /** @test */
    public function create_basic_room_returns_json_of_new_group()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $input = [
            "name" => "Room name",
            "description" => "Some Room Description",
        ];

        $response = $this
                    ->actingAs($user, 'api')
                    ->json('POST', '/api/groups', $input);

        $response->assertStatus(200);

        $this->assertEquals($input['name'], $response->json()['name']);
        $this->assertEquals($input['description'], $response->json()['description']);
    }

    /** @test */
    function user_must_be_logged_in_to_create_a_group()
    {
        $input = [
            "name" => "Room name",
            "description" => "Some Room Description",
        ];

        $response = $this
                    ->json('POST', '/api/rooms', $input);

        $response->assertStatus(401);

        $groups = Group::all();

        $this->assertEquals(0, $groups->count());
    }

    /** @test */
    function group_must_have_a_name()
    {
        // $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $input = [
            "name" => "",
            "description" => "Some Room Description",
        ];

        $response = $this
                    ->actingAs($user, 'api')
                    ->json('POST', '/api/groups', $input);

        $response->assertStatus(422);

        $groups = Group::all();
        $this->assertEquals(0, $groups->count());
        $this->assertArrayHasKey('name', $response->Json()['errors']);
    }

    /** @test */
    function group_must_have_a_unique_name()
    {
        // $this->withoutExceptionHandling();

        $group = factory(Group::class)->create([
            "name" => "Duplicate Name"
        ]);

        $user = factory(User::class)->create();

        $input = [
            "name" => "Duplicate Name",
        ];

        $response = $this
                    ->actingAs($user, 'api')
                    ->json('POST', '/api/groups', $input);

        $response->assertStatus(422);

        $groups = Group::all();
        $this->assertEquals(1, $groups->count());
        $this->assertArrayHasKey('name', $response->Json()['errors']);
    }

    /** @test */
    function user_is_added_as_owner_to_group_they_create()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $input = [
            "name" => "Room name",
            "description" => "Some Room Description",
        ];

        $response = $this
                    ->actingAs($user, 'api')
                    ->json('POST', 'api/groups', $input);

        $response->assertStatus(200);

        $groups = Group::all();

        $this->assertEquals(1, $groups->first()->owners()->count());
        $this->assertEquals($user->id, $groups->first()->owners()->first()->id);
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

        $groups = Group::all();

        $this->assertEquals(1, $groups->first()->users()->count());
        $this->assertEquals($user->id, $groups->first()->users()->first()->id);
    }
}
