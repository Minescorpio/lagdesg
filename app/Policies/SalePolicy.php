<?php

namespace App\Policies;

use App\Models\Sale;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SalePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view-sales');
    }

    public function view(User $user, Sale $sale): bool
    {
        return $user->hasPermissionTo('view-sales');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create-sales');
    }

    public function update(User $user, Sale $sale): bool
    {
        return $user->hasPermissionTo('edit-sales');
    }

    public function delete(User $user, Sale $sale): bool
    {
        return $user->hasPermissionTo('delete-sales');
    }

    public function void(User $user, Sale $sale): bool
    {
        return $user->hasPermissionTo('void-sales');
    }
} 