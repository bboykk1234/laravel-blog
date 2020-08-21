<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testRegisterNewUser()
    {
        $response = $this->post('/api/auth/register', [
            'data' => [
                'name' => 'test',
                'email' => 'test@1234.com',
                'password' => 'abcd1234',
                'password_confirmation' => 'abcd1234',
            ]
        ]);

        $response->assertStatus(201);
    }
}
