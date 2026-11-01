<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Gate 'is-owner': Hanya mengizinkan User dengan role 'owner'
        Gate::define('is-owner', function (User $user) {
            return $user->role === 'owner';
        });

        // Gate 'is-staff': Mengizinkan Owner dan Staff untuk mengelola ritel
        Gate::define('is-staff', function (User $user) {
            return in_array($user->role, ['owner', 'staff']);
        });

        // Gate 'is-customer': Mengizinkan User dengan role 'customer'
        Gate::define('is-customer', function (User $user) {
            return $user->role === 'customer';
        });

        // Gate 'admin': Digunakan untuk mengecek akses kelola master data (Owner/Admin)
        Gate::define('admin', function (User $user) {
            return in_array(strtolower($user->role), ['admin', 'owner']);
        });
    }
}