<?php

namespace Corals\Modules\TroubleTicket\Scopes;

use Corals\Foundation\Contracts\CoralsScope;

class ClassifiedProductsScope implements CoralsScope
{
    public function apply($builder, $extras = [])
    {
        if (user() && user()->hasPermissionTo('Administrations::admin.classified')) {
            return;
        }

        $builder->where('status', 'active');
    }
}
