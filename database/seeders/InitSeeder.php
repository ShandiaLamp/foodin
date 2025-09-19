<?php

namespace Shandialamp\Foodin\Database\Seeders;

use Illuminate\Database\Seeder;
use Shandialamp\Foodin\Services\UserService;


class InitSeeder extends Seeder
{
    protected UserService $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    public function run(): void
    {
        if ($this->userService->count() == 0) {
            $this->userService->create(
                'admin',
                '12345678',
                '13800000000',
                '管理员',
            );
        }
    }
}
