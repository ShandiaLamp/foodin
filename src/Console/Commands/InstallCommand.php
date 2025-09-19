<?php

namespace Shandialamp\Foodin\Console\Commands;

use Illuminate\Console\Command;
use Shandialamp\Foodin\Database\Seeders\InitSeeder;
use Shandialamp\Foodin\FoodinServiceProvider;

class InstallCommand extends Command
{
    protected $signature = 'foodin:install';

    // 命令描述
    protected $description = '安装';

    public function handle(): void
    {
        $this->call('db:seed', [
            '--class' => InitSeeder::class,
        ]);
    }
}
