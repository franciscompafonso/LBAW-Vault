<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AdminPolicy
{
    
    public function create(Admin $admin): bool
    {
        $isAdmin = auth()->guard('admin')->check();
        return $isAdmin;
    }

    public function manageUsers(Admin $admin)
    {
        $isAdmin = auth()->guard('admin')->check();
        return $isAdmin;
    }

    public function deleteComment(Admin $admin)
    {
        $isAdmin = auth()->guard('admin')->check();
        return $isAdmin;
    }

}
