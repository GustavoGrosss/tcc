<?php

namespace App\Providers;

use App\Models\Lembretes;
use App\Models\Role;
use App\Models\User;
use App\Policies\LembretesPolicy;
use App\Policies\PermissionPolicy;
use App\Policies\RolePolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Spatie\Permission\Models\Permission;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
//        User::class       => UserPolicy::class,
//        Permission::class => PermissionPolicy::class,
//        Lembretes::class  => LembretesPolicy::class,
//        Role::class       => RolePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
