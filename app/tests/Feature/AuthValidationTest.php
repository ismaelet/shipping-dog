<?php

namespace Tests\Feature;

use Tests\TestCase;

class AuthValidationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware();
    }

    public function test_registration_requires_an_email_and_matching_password_confirmation(): void
    {
        $response = $this->from('/register')->post('/register', [
            'email' => 'not-an-email',
            'password' => 'password',
            'password_confirmation' => 'different-password',
        ]);

        $response
            ->assertRedirect('/register')
            ->assertSessionHasErrors(['email', 'password']);
    }

    public function test_login_requires_valid_credentials(): void
    {
        $response = $this->from('/login')->post('/login', [
            'email' => 'not-an-email',
            'password' => '',
        ]);

        $response
            ->assertRedirect('/login')
            ->assertSessionHasErrors(['email', 'password']);
    }
}
