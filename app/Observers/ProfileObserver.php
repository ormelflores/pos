<?php

namespace App\Observers;

use App\Actions\GenerateActivityLog;
use App\Models\Profile;

class ProfileObserver
{
    /**
     * Handle the Profile "created" event.
     */
    public function created(Profile $profile): void
    {
        //
    }

    /**
     * Handle the Profile "updated" event.
     */
    public function updated(Profile $profile): void
    {
        GenerateActivityLog::run('Updated', $profile, "Edited {$profile->fullName} profile information");
    }

    /**
     * Handle the Profile "deleted" event.
     */
    public function deleted(Profile $profile): void
    {
        //
    }

    /**
     * Handle the Profile "restored" event.
     */
    public function restored(Profile $profile): void
    {
        //
    }

    /**
     * Handle the Profile "force deleted" event.
     */
    public function forceDeleted(Profile $profile): void
    {
        //
    }
}
