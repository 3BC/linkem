<?php

namespace Tests\Feature;

use App\Group;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EditGroupTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_edit_a_group_name()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $group = factory(Group::class)->create([
            'name' => "Original Group Name",
            "description" => "Some Group Description",
        ]);

        $group->owners()->attach($user->id);

        $input = [
            "name" => "New Group Name",
            "description" => "Some Group Description",
        ];

        $response = $this
                    ->actingAs($user, 'api')
                    ->json('PATCH', 'api/groups/'.$group->id, $input);

        $response->assertStatus(200);

        $groups = Group::all();

        $this->assertEquals(1, $groups->count());
        $this->assertEquals($input['name'], $groups->first()->name);
        $this->assertEquals($input['description'], $groups->first()->description);
    }

    /** @test */
    public function create_basic_room_returns_json_of_new_group()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $group = factory(Group::class)->create([
            'name' => "Original Room Name",
            "description" => "Some Room Description",
        ]);

        $group->owners()->attach($user->id);

        $input = [
            "name" => "New Room Name",
            "description" => "Some Room Description",
        ];

        $response = $this
                    ->actingAs($user, 'api')
                    ->json('PATCH', 'api/groups/'.$group->id, $input);

        $response->assertStatus(200);

        $this->assertEquals($input['name'], $response->json()['name']);
        $this->assertEquals($input['description'], $response->json()['description']);
    }

    /** @test */
    public function user_must_be_logged_in_to_edit_a_group()
    {
        $group = factory(Group::class)->create([
            'name' => "Original Room Name",
            "description" => "Some Room Description",
        ]);

        $input = [
            "name" => "New Room Name",
            "description" => "Some Room Description",
        ];

        $response = $this
                    ->json('PATCH', 'api/groups/'.$group->id, $input);

        $response->assertStatus(401);
    }

    /** @test */
    public function group_must_have_a_name_when_editing()
    {
        // $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $group = factory(Group::class)->create([
            'name' => "Original Room Name",
            "description" => "Some Room Description",
        ]);

        $group->owners()->attach($user->id);

        $input = [
            "name" => "",
            "description" => "Some Room Description",
        ];

        $response = $this
                    ->actingAs($user, 'api')
                    ->json('PATCH', 'api/groups/'.$group->id, $input);

        $response->assertStatus(422);

        $this->assertArrayHasKey('name', $response->Json()['errors']);
    }

    /** @test */
    public function group_must_have_a_unique_name_when_editing()
    {
        // $this->withoutExceptionHandling();

        $groupOne = factory(Group::class)->create([
            "name" => "Room Name"
        ]);

        $groupTwo = factory(Group::class)->create([
            "name" => "Duplicate Name"
        ]);

        $user = factory(User::class)->create();

        $groupOne->owners()->attach($user->id);

        $input = [
            "name" => "Duplicate Name",
        ];

        $response = $this
                    ->actingAs($user, 'api')
                    ->json('PATCH', 'api/groups/'.$groupOne->id, $input);

        $response->assertStatus(422);
        $this->assertArrayHasKey('name', $response->Json()['errors']);
    }

    /** @test */
    public function group_can_be_updated_with_same_information()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $group = factory(Group::class)->create([
            'name' => "Original Room Name",
            "description" => "Some Room Description",
        ]);

        $group->owners()->attach($user->id);

        $input = [
            "name" => "Original Room Name",
            "description" => "Some Room Description",
        ];

        $response = $this
                    ->actingAs($user, 'api')
                    ->json('PATCH', 'api/groups/'.$group->id, $input);

        $response->assertStatus(200);

        $this->assertEquals($input['name'], $response->json()['name']);
        $this->assertEquals($input['description'], $response->json()['description']);
    }
}
