<?php

namespace Shandialamp\Foodin\Tests;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase;

class AuthTest extends TestCase
{
    use DatabaseTransactions;

    public function test_login_success()
    {
        $response = $this->postJson('/api/admin/auth/login', [
            'username'  => 'admin',
            'password'  => '12345678',
        ]);

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertNull( $data['error']);
    }

    public function test_login_fail()
    {
        $response = $this->postJson('/api/admin/auth/login', [
            'username'  => 'admin',
            'password'  => '123456',
        ]);

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertNotNull( $data['error']);
    }
}
