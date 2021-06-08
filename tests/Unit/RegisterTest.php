<?php

namespace Tests\Unit;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class RegisterTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function test_register_should_be_validated()
    {
        $response = $this->postJson(route('auth.register'));
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_new_user_can_be_registerd()
    {
        $response = $this->postJson(route('auth.register'), [
            'name' => $this->faker()->name,
            'email' => $this->faker()->safeEmail,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        ]);
        $response->assertStatus(Response::HTTP_CREATED);
    }

    public function test_information_of_user_must_be_validated()
    {
        $response = $this->postJson(route('auth.login'));
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_user_can_be_login()
    {
        $user = factory(User::class)->create();
        $response = $this->postJson(route('auth.login'), [
            'email' => $user->email,
            'password' => 'password'
        ]);
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_show_user_info_if_logged_in()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)->getJson(route('auth.user'));
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_user_can_be_logout()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)->postJson(route('auth.logout'));
        $response->assertStatus(Response::HTTP_OK);
    }
}
