<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Sale;
use App\Policies\SalePolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Sale::class => SalePolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
} 