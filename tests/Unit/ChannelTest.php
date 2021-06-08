<?php

namespace Tests\Unit;

use App\Channel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class ChannelTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function test_all_channels_list_should_be_accessible()
    {
        $response = $this->get(route('channel.all'));
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_create_channel_should_be_validated()
    {
        $response = $this->postJson(route('channel.create'), [
            'name' => null
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_create_new_channel()
    {
        $response = $this->postJson(route('channel.create'), [
            'name' => $this->faker->name
        ]);
        $response->assertStatus(Response::HTTP_CREATED);
    }

    public function test_update_channel_should_be_validated()
    {
        $response = $this->json('PUT', route('channel.update'), [
            'name' => null
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_channel_can_be_updated()
    {
        $channel = factory(Channel::class)->create();
        $response = $this->json('PUT', route('channel.update'), [
            'id' => Channel::all()->random()->id,
            'name' => $channel->name
        ]);
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_delete_channel_should_be_validated()
    {
        $response = $this->json('DELETE', route('channel.delete'), [
            'id' => null,
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_channel_can_be_deleted()
    {
        $channel = factory(Channel::class)->create();
        $response = $this->json('DELETE', route('channel.delete'), [
            'id' => $channel->id
        ]);
        $response->assertStatus(Response::HTTP_OK);
    }
}
