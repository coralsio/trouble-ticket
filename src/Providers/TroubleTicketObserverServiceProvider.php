<?php

namespace Corals\Modules\TroubleTicket\Providers;

use Corals\Modules\TroubleTicket\Models\TroubleTicket;
use Corals\Modules\TroubleTicket\Observers\TroubleTicketObserver;
use Illuminate\Support\ServiceProvider;

class TroubleTicketObserverServiceProvider extends ServiceProvider
{
    /**
     * Register Observers
     */
    public function boot()
    {
        TroubleTicket::observe(TroubleTicketObserver::class);
    }
}
