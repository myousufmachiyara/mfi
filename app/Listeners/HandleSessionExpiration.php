<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Session\Events\SessionExpired;
use App\Models\User;

class HandleSessionExpiration
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        if (session()->has('user_id')) {
            // Update the user's is_login status
            users::where('id', session('user_id'))->update([
                'is_login'=>0,
            ]);
        }
    }
}
