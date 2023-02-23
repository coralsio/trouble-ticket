<?php

namespace Corals\Modules\TroubleTicket;

use Corals\Foundation\Providers\BasePackageServiceProvider;
use Corals\Modules\TroubleTicket\Facades\TroubleTickets;
use Corals\Modules\TroubleTicket\Models\Assignment;
use Corals\Modules\TroubleTicket\Models\IssueType;
use Corals\Modules\TroubleTicket\Models\Team;
use Corals\Modules\TroubleTicket\Models\TroubleTicket;
use Corals\Modules\TroubleTicket\Notifications\TTAssignmentNotification;
use Corals\Modules\TroubleTicket\Notifications\TTStatusChangedNotification;
use Corals\Modules\TroubleTicket\Providers\TroubleTicketAuthServiceProvider;
use Corals\Modules\TroubleTicket\Providers\TroubleTicketObserverServiceProvider;
use Corals\Modules\TroubleTicket\Providers\TroubleTicketRouteServiceProvider;
use Corals\Modules\Utility\Facades\Utility;
use Corals\Settings\Facades\Modules;
use Corals\Settings\Facades\Settings;
use Corals\User\Communication\Facades\CoralsNotification;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\AliasLoader;

class TroubleTicketServiceProvider extends BasePackageServiceProvider
{
    /**
     * @var
     */
    protected $defer = true;
    /**
     * @var
     */
    protected $packageCode = 'corals-trouble-ticket';

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function bootPackage()
    {
        // Load view
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'TroubleTicket');

        // Load translation
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'TroubleTicket');

        // Load migrations
//        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        $this->registerMorphMaps();
        $this->registerCustomFieldsModels();

        $this->addEvents();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function registerPackage()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/trouble_ticket.php', 'trouble_ticket');

        $this->app->register(TroubleTicketRouteServiceProvider::class);
        $this->app->register(TroubleTicketAuthServiceProvider::class);
        $this->app->register(TroubleTicketObserverServiceProvider::class);

        $this->app->booted(function () {
            $loader = AliasLoader::getInstance();
            $loader->alias('TroubleTickets', TroubleTickets::class);
        });

        Utility::addToUtilityModules('TroubleTicket');
    }

    protected function registerCustomFieldsModels()
    {
        Settings::addCustomFieldModel(TroubleTicket::class);
    }

    protected function registerMorphMaps()
    {
        Relation::morphMap([
            'TroubleTicket' => TroubleTicket::class,
            'Assignment' => Assignment::class,
            'Team' => Team::class,
            'IssueType' => IssueType::class,
        ]);
    }

    protected function addEvents()
    {
        CoralsNotification::addEvent(
            'notifications.trouble_tickets.status_changed',
            'Support Ticket status changed',
            TTStatusChangedNotification::class
        );

        CoralsNotification::addEvent(
            'notifications.trouble_tickets.assignment',
            'Support Ticket assignment',
            TTAssignmentNotification::class
        );
    }

    public function registerModulesPackages()
    {
        Modules::addModulesPackages('corals/trouble-ticket');
    }
}
