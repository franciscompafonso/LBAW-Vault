<?php

namespace App\Policies;

use App\Models\Admin;

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

}
