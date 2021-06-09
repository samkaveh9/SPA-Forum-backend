<?php

namespace Tests\Unit;

use App\Answer;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AnswerTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function test_all_answers_list()
    {
        $response = $this->get(route('answers.index'));
        $response->assertSuccessful();
    }

    public function test_send_answer_should_be_validated()
    {
        Sanctum::actingAs(factory(User::class)->create());
        $response = $this->postJson(route('answers.store'),[]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_submit_new_answer_for_thread()
    {
        Sanctum::actingAs(factory(User::class)->create());
        $thread = factory(Thread::class)->create();
        $response = $this->postJson(route('answers.store'),[
            'content' => $this->faker()->sentence,
            'thread_id' => $thread->id
        ]);
        $response->assertStatus(Response::HTTP_CREATED);
    }

    public function test_user_score_will_increase_by_submit_new_answer()
    {
        $user = factory(User::class)->create();
        Sanctum::actingAs($user);
        $thread = factory(Thread::class)->create();
        $response = $this->postJson(route('answers.store'),[
            'content' => $this->faker()->sentence,
            'thread_id' => $thread->id
        ]);
        $response->assertStatus(Response::HTTP_CREATED);
        $user->refresh();
        $this->assertEquals(10, $user->score);
    }

    public function test_update_answer_should_be_validated()
    {
        Sanctum::actingAs(factory(User::class)->create());
        $answer = factory(Answer::class)->create();
        $response = $this->putJson(route('answers.update',[$answer]),[]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_user_can_update_own_answer_of_thread()
    {
        $user = factory(User::class)->create();
        Sanctum::actingAs($user);
        $answer = factory(Answer::class)->create([
            'content' => $this->faker()->realText(),
            'user_id' => $user->id
        ]);
        $response = $this->putJson(route('answers.update',[$answer]),[
            'content' => $this->faker()->realText()
        ]);
        $response->assertStatus(Response::HTTP_OK);
        $answer->refresh();
        $response->assertJson([
            'message' => 'answer updated successfuly'
        ]);
    }

    function test_user_can_delete_own_answer()
    {
        $user = factory(User::class)->create();
        Sanctum::actingAs($user);
        $answer = factory(Answer::class)->create([
            'user_id' => $user->id
        ]);
        $response = $this->delete(route('answers.destroy',[$answer]));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'message' => 'answer deleted successfuly'
        ]);
       $this->assertFalse(Thread::find($answer->thread_id)->answers()->whereContent($answer->content)->exists());
    }
}
