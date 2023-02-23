<?php

namespace Corals\Modules\TroubleTicket\Providers;

use Corals\Foundation\Providers\BaseUninstallModuleServiceProvider;
use Corals\Modules\TroubleTicket\database\migrations\CreateTroubleTicketsTable;
use Corals\Modules\TroubleTicket\database\seeds\TroubleTicketDatabaseSeeder;

class UninstallModuleServiceProvider extends BaseUninstallModuleServiceProvider
{
    protected $migrations = [
        CreateTroubleTicketsTable::class
    ];

    protected function providerBooted()
    {
        $this->dropSchema();

        $troubleTicketDatabaseSeeder = new TroubleTicketDatabaseSeeder();

        $troubleTicketDatabaseSeeder->rollback();
    }
}
