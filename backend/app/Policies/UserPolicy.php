<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    public function manageUsers(User $user)
    {
        return in_array('Администрирование', $user->access);
    }

    public function viewSelf(User $user, User $targetUser)
    {
        return $user->id === $targetUser->id || in_array('Администрирование', $user->access);
    }

    public function view(User $user)
    {
        return $user->hasAccessToAdminModule();
    }

    public function create(User $user)
    {
        return $user->hasAccessToAdminModule();
    }

    public function update(User $user)
    {
        return $user->hasAccessToAdminModule();
    }
}
