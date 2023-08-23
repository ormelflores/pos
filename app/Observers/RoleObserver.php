<?php

namespace App\Observers;

use App\Actions\GenerateActivityLog;
use App\Models\Role;

class RoleObserver
{
    /**
     * Handle the Role "created" event.
     */
    public function created(Role $role): void
    {
        GenerateActivityLog::run('Created', $role, "Added new role named {$role->name}");
    }

    /**
     * Handle the Role "updated" event.
     */
    public function updated(Role $role): void
    {
        GenerateActivityLog::run('Updated', $role, "Edited {$role->name} role");
    }

    /**
     * Handle the Role "deleted" event.
     */
    public function deleted(Role $role): void
    {
        GenerateActivityLog::run('Deleted', $role, "Deleted {$role->name} role");
    }

    /**
     * Handle the Role "restored" event.
     */
    public function restored(Role $role): void
    {
        //
    }

    /**
     * Handle the Role "force deleted" event.
     */
    public function forceDeleted(Role $role): void
    {
        //
    }
}