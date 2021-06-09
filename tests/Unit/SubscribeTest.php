<?php

namespace Tests\Unit;

use App\Notifications\NewReplaySubmitted;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SubscribeTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function test_user_can_subscribe_to_a_channel()
    {
        Sanctum::actingAs(factory(User::class)->create());
        $thread = factory(Thread::class)->create();
        $response = $this->post(route('subscribe',[$thread]));
        $response->assertSuccessful();
        $response->assertJson([
            'message' => 'user subscribed successfuly'
        ]);
    }

    public function test_user_can_unsubscribe_to_a_channel()
    {
        Sanctum::actingAs(factory(User::class)->create());
        $thread = factory(Thread::class)->create();
        $response = $this->post(route('unsubscribe',[$thread]));
        $response->assertSuccessful();
        $response->assertJson([
            'message' => 'user unsubscribed successfuly'
        ]);
    }

    public function test_notification_will_send_to_subscribers_of_a_thread()
    {
        $user = factory(User::class)->create();
        Sanctum::actingAs($user);
        Notification::fake();

        $thread = factory(Thread::class)->create();
        $subscribe_response = $this->post(route('subscribe',[$thread]));
        $subscribe_response->assertSuccessful();
        $subscribe_response->assertJson([
            'message' => 'user subscribed successfuly'
        ]);

        $answer_response = $this->postJson(route('answers.store'),[
            'content' => $this->faker()->realText(),
            'thread_id' => $thread->id
        ]);

        $answer_response->assertSuccessful();
        $answer_response->assertJson([
            'message' => 'answer submitted successfuly'
        ]);

        Notification::assertSentTo($user, NewReplaySubmitted::class);
    }
}
