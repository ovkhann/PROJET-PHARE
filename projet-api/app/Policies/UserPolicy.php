<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function store(?User $user, User $userTarget)
    {
        if ($user->IsAdmin()) {
        return true;
    }
        return false;
    }
    public function update(?User $user, User $userTarget)

    {
        if ($user->IsAdmin()) {
        return true;
    }
        return $user->id == $userTarget->id;
    }
        public function destroy(User $user, User $userTarget)
    {
        if ($user->IsAdmin()) {
        return true;
    }
        return $user->id == $userTarget->id;
    }
}