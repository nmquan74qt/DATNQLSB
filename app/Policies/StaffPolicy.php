<?php

namespace App\Policies;

use App\Models\User;

class StaffPolicy
{
    /**
     * Determine whether the user can manage staff (view, attendance, payroll)
     */
    public function manage(User $user): bool
    {
        return $user->role === 'admin';
    }
}
