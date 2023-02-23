<?php

namespace Corals\Modules\TroubleTicket\Scopes;

use Corals\Foundation\Contracts\CoralsScope;

class DirectoryListingsScope implements CoralsScope
{

    public function apply($builder, $extras = [])
    {
        if (user() && user()->hasPermissionTo('Administrations::admin.directory')) {
            return;
        }

        $builder->where('status', 'active');
    }
}
