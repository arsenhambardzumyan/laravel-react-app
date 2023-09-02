<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        // Define how user data should be generated
        return [
            'name' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('password'), // Default password
            'access' => $this->faker->randomElements([
                'Администрирование', 'Билеты', 'Аналитика', 'Уведомления', 'Живая лента', 'Обращения'
            ], $this->faker->numberBetween(1, 6)),
            'access' => [], // Define access permissions here
            'blocked' => false, // Default is not blocked
            'email_verified_at' => now(),
        ];
    }
}
