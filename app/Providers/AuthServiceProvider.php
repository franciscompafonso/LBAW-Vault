<?php

namespace App\Providers;
use App\Models\User;
use App\Policies\UserPolicy;

use App\Models\Event;
use App\Policies\EventPolicy;
use App\Models\Admin;
use App\Policies\AdminPolicy;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Event::class => EventPolicy::class,
        Admin::class => AdminPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('show', function ($currentUser, $user) {
            return $currentUser->id === $user->id || $currentUser->is_admin;
        });

        Gate::define('edit_profile', [UserPolicy::class, 'edit_profile']);
    }
}
