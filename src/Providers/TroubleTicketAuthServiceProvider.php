<?php

namespace Corals\Modules\TroubleTicket\Providers;

use Corals\Modules\TroubleTicket\Models\IssueType;
use Corals\Modules\TroubleTicket\Models\Team;
use Corals\Modules\TroubleTicket\Models\TroubleTicket;
use Corals\Modules\TroubleTicket\Policies\IssueTypePolicy;
use Corals\Modules\TroubleTicket\Policies\TeamPolicy;
use Corals\Modules\TroubleTicket\Policies\TroubleTicketPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class TroubleTicketAuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        TroubleTicket::class => TroubleTicketPolicy::class,
        Team::class => TeamPolicy::class,
        IssueType::class => IssueTypePolicy::class,
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
