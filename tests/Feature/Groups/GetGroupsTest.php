<?php

namespace Tests\Feature;

use App\Group;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteGroupsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_list_of_all_groups()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $groups = factory(Group::class, 5)->create();

        $response = $this
                    ->actingAs($user, 'api')
                    ->json('GET', '/api/groups/list');

        $response->assertStatus(200);

        $this->assertEquals(5, count($response->json()));

    }

    /** @test */
    public function it_returns_list_of_all_groups_the_user_follows()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $groups = factory(Group::class, 5)->create();
        $user_groups = factory(Group::class, 3)->create();

        foreach ($user_groups as $group) {
          $user->groups()->attach($group->id);
        }

        $response = $this
                    ->actingAs($user, 'api')
                    ->json('GET', '/api/groups');

        $response->assertStatus(200);

        $this->assertEquals(3, count($response->json()));

    }

    /** @test */
    public function user_must_be_logged_in_to_get_list_of_rooms_they_have()
    {
        $response = $this
                    ->json('GET', '/api/groups');

        $response->assertStatus(401);
    }
}
