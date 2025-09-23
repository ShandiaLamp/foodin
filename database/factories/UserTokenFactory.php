<?php

namespace Shandialamp\Foodin\Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Shandialamp\Foodin\Models\UserToken;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Shandialamp\Foodin\Models\UserToken>
 */
class UserTokenFactory extends Factory
{
    protected $model = UserToken::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'token' => fake()->regexify('[A-Za-z0-9]{32}'),
            'user_id'   => fake()->randomNumber(),
            'scope'    => 'web',
            'expire_at' => (new Carbon())->addDay(),
        ];
    }
}
