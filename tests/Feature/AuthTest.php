<?php

use App\Modules\User\Mail\WelcomeMail;
use App\Modules\User\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

it('registers a user and returns a token', function () {
    Mail::fake();

    $payload = [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'password' => 'password123',
    ];

    $response = $this->postJson('/api/auth/register', $payload);

    $response
        ->assertCreated()
        ->assertJsonStructure([
            'access_token',
            'token_type',
            'expires_in',
            'user' => ['id', 'name', 'email'],
        ]);

    $this->assertDatabaseHas('users', ['email' => 'jane@example.com']);

    Mail::assertSent(WelcomeMail::class, function (WelcomeMail $mail) {
        return $mail->hasTo('jane@example.com')
            && $mail->user->name === 'Jane Doe';
    });
});

it('logs in a user and returns a token', function () {
    $user = User::factory()->create([
        'password' => Hash::make('secret123'),
    ]);

    $response = $this->postJson('/api/auth/login', [
        'email' => $user->email,
        'password' => 'secret123',
    ]);

    $response
        ->assertOk()
        ->assertJsonStructure([
            'access_token',
            'token_type',
            'expires_in',
            'user' => ['id', 'name', 'email'],
        ]);
});

it('requires authentication for me endpoint', function () {
    $this->getJson('/api/auth/me')->assertUnauthorized();
});
