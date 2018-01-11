<?php

namespace Tests\Feature;

use App\Link;
use App\User;
use App\Group;
use Tests\TestCase;
use Illuminate\Session\Store;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteLinkTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function delete_basic_link()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $groups = factory(Group::class, 1)->create();

        foreach($groups as $group)
        {
          $user->groups()->attach($group->id);
          $group->moderators()->attach($user->id);

          for($i=0; $i<5; $i++)
          {
              Link::create([
                'url' => "https://example-no-$i.com",
                'name' => "Example $i",
                'description' => "A description for link $i",
                'group_id' => $group->id,
                'user_id' => $user->id
              ]);
          }
        }

        $link_id = 1;

        $response = $this
        ->actingAs($user, 'api')
        ->json('DELETE', '/api/links/'.$link_id);

        $response->assertStatus(200);

        $link = Link::where('id', $link_id)->first();

        $this->assertEquals(0, count($link));

    }

    /** @test */
    public function edit_basic_link_requires_user_to_be_moderator()
    {
      //$this->withoutExceptionHandling();
      $user = factory(User::class)->create();

      $groups = factory(Group::class, 1)->create();

      foreach($groups as $group)
      {
        $user->groups()->attach($group->id);

        for($i=0; $i<5; $i++)
        {
            Link::create([
              'url' => "example-no-$i",
              'name' => "Example $i",
              'description' => "A description for link $i",
              'group_id' => $group->id,
              'user_id' => $user->id
            ]);
        }
      }

      $link_id = 1;

      $response = $this
      ->actingAs($user, 'api')
      ->json('DELETE', '/api/links/'.$link_id);


      $response->assertStatus(403);

    }


}
