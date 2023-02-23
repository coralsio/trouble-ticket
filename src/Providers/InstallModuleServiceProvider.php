<?php

namespace Corals\Modules\TroubleTicket\Providers;

use Corals\Foundation\Providers\BaseInstallModuleServiceProvider;
use Corals\Modules\TroubleTicket\database\migrations\CreateTroubleTicketsTable;
use Corals\Modules\TroubleTicket\database\seeds\TroubleTicketDatabaseSeeder;

class InstallModuleServiceProvider extends BaseInstallModuleServiceProvider
{
    protected $module_public_path = __DIR__ . '/../public';

    protected $migrations = [
        CreateTroubleTicketsTable::class
    ];

    protected function providerBooted()
    {
        $this->createSchema();

        $troubleTicketDatabaseSeeder = new TroubleTicketDatabaseSeeder();

        $troubleTicketDatabaseSeeder->run();
    }
}
