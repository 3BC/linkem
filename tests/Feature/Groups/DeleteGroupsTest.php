<?php

namespace Tests\Feature;

use App\Group;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetGroupsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_delete_a_group()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $groups = factory(Group::class, 5)->create();

        foreach ($groups as $group) {
          $group->owners()->attach($user->id);
        }

        $response = $this
                    ->actingAs($user, 'api')
                    ->json('DELETE', '/api/groups/1');

        $response->assertStatus(200);

    }

    /** @test */
    public function user_must_own_group_to_delete_a_group()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $groups = factory(Group::class, 5)->create();

        $response = $this
                    ->actingAs($user, 'api')
                    ->json('DELETE', '/api/groups/1');

        $response->assertStatus(403);

    }

    /** @test */
    public function user_must_be_logged_in_to_delete_a_group()
    {
        $user = factory(User::class)->create();

        $groups = factory(Group::class, 5)->create();

        $response = $this
                    ->json('DELETE', '/api/groups/1');

        $response->assertStatus(401);
    }
}
