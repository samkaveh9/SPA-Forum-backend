<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use App\User;
use App\Thread;
use App\Channel;
use Laravel\Sanctum\Sanctum;

class ThreadTest extends TestCase
{
    use RefreshDatabase, WithFaker;
   
    public function test_threads_list_should_be_accessible()
    {
        $response = $this->get(route('threads.index'));
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_thread_should_be_accessible_by_slug()
    {
        $response = $this->get(route('threads.show',$this->faker()->slug));
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_create_thread_should_be_validated()
    {
        $response = $this->postJson(route('threads.store'),[]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_thread_can_be_created()
    {
        Sanctum::actingAs(factory(User::class)->create());
        $response = $this->postJson(route('threads.store'),[
            'title' => $this->faker()->title,
            'content' => $this->faker()->sentence,
            'channel_id' => factory(Channel::class)->create()->id,
        ]);
        $response->assertStatus(Response::HTTP_CREATED);
    }

    public function test_update_thread_should_be_validated()
    {    
        Sanctum::actingAs(factory(User::class)->create());
        $thread = $this->createThread();
        $response = $this->putJson(route('threads.update',[$thread]),[]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_thread_can_be_updated()
    {
        $user = factory(User::class)->create();
        Sanctum::actingAs($user);
        $thread = factory(Thread::class)->create([
            'title' => $this->faker()->title,
            'content' => $this->faker()->sentence,
            'channel_id' => factory(Channel::class)->create()->id,
            'user_id' => $user->id
        ]);

        $response = $this->putJson(route('threads.update',[$thread]),[
            'title' => $this->faker()->title,
            'content' => $this->faker()->sentence,
            'channel_id' => factory(Channel::class)->create()->id
        ])->assertSuccessful();
    }

    public function test_can_add_best_answer_id_for_thread()
    {
        $user = factory(User::class)->create();
        Sanctum::actingAs($user);
        $thread = factory(Thread::class)->create([
            'user_id' => $user->id
        ]);
        $response = $this->putJson(route('threads.update',[$thread]),[
            'best_answer_id' => $thread->id,
        ])->assertSuccessful();
    }

    public function test_thread_can_be_deleted()
    {
        $user = factory(User::class)->create();
        Sanctum::actingAs($user);
        $thread = factory(Thread::class)->create([
            'user_id' => $user->id
        ]);
        $response = $this->delete(route('threads.destroy',[$thread->id]));
        $response->assertStatus(Response::HTTP_OK);
    }

    public function createThread()
    {
        return factory(Thread::class)->create([
            'title' => $this->faker()->title,
            'content' => $this->faker()->sentence,
            'channel_id' => factory(Channel::class)->create()->id
        ]);
    }

}
