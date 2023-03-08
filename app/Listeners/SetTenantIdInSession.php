<?php

namespace App\Listeners;

class SetTenantIdInSession
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
        session()->put('tenant_id', auth()->user()->id);
    }
}
