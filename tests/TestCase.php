<?php

namespace Shandialamp\Foodin\Tests;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Shandialamp\Foodin\Models\User;
use Shandialamp\Foodin\Models\UserToken;
use Tymon\JWTAuth\Facades\JWTAuth;

class TestCase extends BaseTestCase
{
    use DatabaseTransactions;


    protected function mockLogin()
    {
        $user = User::first();
        $token = JWTAuth::fromUser($user);
        $user->tokens()->create([
            'token' => $token,
            'scope' => 'test',
            'expire_at' => Carbon::now()->addDay()->format('Y-m-d H:i:s'),
        ]);
        return $this->withHeader('Authorization', 'Bearer ' . $token);
    }
}
