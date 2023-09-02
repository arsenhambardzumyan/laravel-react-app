<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
        {
          User::create([
         'name' => 'admin',
         'lastname' => 'admin',
         'email' => 'admin@example.com',
         'password' => bcrypt('admin'),
         'access' => ['Администрирование', 'Билеты', 'Аналитика', 'Уведомления', 'Живая лента', 'Обращения'],
     ]);
     \App\Models\User::factory(50)->create();

    }
}
