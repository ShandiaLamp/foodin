<?php

namespace Shandialamp\Foodin\Tests;

class AuthTest extends TestCase
{
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

    public function test_user_success()
    {
        $response = $this->mockLogin()->getJson('/api/admin/auth/user');

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertNull( $data['error']);
    }

    public function test_user_fail()
    {
        $response = $this->getJson('/api/admin/auth/user');

        $response->assertStatus(401);
        $data = $response->json();
        $this->assertNotNull( $data['error']);
    }
}
