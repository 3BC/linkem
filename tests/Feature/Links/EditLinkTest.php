<?php

namespace Tests\Feature;

use App\Link;
use App\User;
use App\Group;
use Tests\TestCase;
use Illuminate\Session\Store;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EditLinkTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function edit_basic_link()
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

        $input = [
          "url" => "https://www.updatedlink.com",
          "name" => "This is an updated link name",
          "description" => "This is an updated link description"
        ];

        $response = $this
        ->actingAs($user, 'api')
        ->json('PATCH', '/api/links/'.$link_id, $input);

        $response->assertStatus(200);

        $link = Link::where('id', $link_id)->first();

        $this->assertEquals(1, count($link));
        $this->assertEquals($input['url'], $link->url);
        $this->assertEquals($input['name'], $link->name);
        $this->assertEquals($input['description'], $link->description);
    }

    /** @test */
    public function edit_basic_link_returns_json_of_new_link()
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

      $input = [
        "url" => "https://www.updatedlink.com",
        "name" => "This is an updated link name",
        "description" => "This is an updated link description"
      ];

      $response = $this
      ->actingAs($user, 'api')
      ->json('PATCH', '/api/links/'.$link_id, $input);

      $response->assertStatus(200);

      $this->assertEquals($input['url'], $response->json()['url']);
      $this->assertEquals($input['name'], $response->json()['name']);
      $this->assertEquals($input['description'], $response->json()['description']);
    }

    /** @test */
    public function edit_basic_link_requires_url_param()
    {
        //$this->withoutExceptionHandling();
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

        $input = [
          "url" => "",
          "name" => "This is an updated link name",
          "description" => "This is an updated link description"
        ];

        $response = $this
        ->actingAs($user, 'api')
        ->json('PATCH', '/api/links/'.$link_id, $input);


        $response->assertStatus(422);

        $this->assertArrayHasKey('url', $response->Json()['errors']);
    }

    /** @test */
    public function edit_basic_link_requires_valid_url()
    {
      //$this->withoutExceptionHandling();
      $user = factory(User::class)->create();

      $groups = factory(Group::class, 1)->create();

      foreach($groups as $group)
      {
        $user->groups()->attach($group->id);
        $group->moderators()->attach($user->id);

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

      $input = [
        "url" => "",
        "name" => "This is an updated link name",
        "description" => "This is an updated link description"
      ];

      $response = $this
      ->actingAs($user, 'api')
      ->json('PATCH', '/api/links/'.$link_id, $input);


      $response->assertStatus(422);

      $this->assertArrayHasKey('url', $response->Json()['errors']);
    }

    /** @test */
    public function edit_basic_link_without_name()
    {
      //$this->withoutExceptionHandling();

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
              'name' => "Example name",
              'description' => "A description for link $i",
              'group_id' => $group->id,
              'user_id' => $user->id
            ]);
        }
      }

      $link_id = 1;

      $input = [
        "url" => "https://example-no-$i.co",
        "name" => "",
        "description" => "This is an updated link description"
      ];

      $response = $this
      ->actingAs($user, 'api')
      ->json('PATCH', '/api/links/'.$link_id, $input);


      $response->assertStatus(200);
    }

    /** @test */
    public function edit_basic_link_without_description()
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
                'name' => "Example name",
                'description' => "A description for link $i",
                'group_id' => $group->id,
                'user_id' => $user->id
              ]);
          }
        }

        $link_id = 1;

        $input = [
          "url" => "https://example-no-$i.co",
          "name" => "Example name",
          "description" => ""
        ];

        $response = $this
        ->actingAs($user, 'api')
        ->json('PATCH', '/api/links/'.$link_id, $input);


        $response->assertStatus(200);
    }

}
