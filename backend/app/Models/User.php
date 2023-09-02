<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name', 'lastname', 'email', 'password', 'access', 'blocked'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'access' => 'json', // Преобразование доступов в JSON
        'blocked' => 'boolean', // Преобразование блокировки в булевое значение
    ];

    // Метод для проверки, имеет ли пользователь доступ к модулю "Администрирование"
    public function hasAdminAccess()
    {
        return in_array('Администрирование', $this->access);
    }
}
