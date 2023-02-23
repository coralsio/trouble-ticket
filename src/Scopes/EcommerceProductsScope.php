<?php

namespace Corals\Modules\TroubleTicket\Scopes;

use Corals\Foundation\Contracts\CoralsScope;

class EcommerceProductsScope implements CoralsScope
{

    public function apply($builder, $extras = [])
    {
        if (user() && user()->hasPermissionTo('Administrations::admin.ecommerce')) {
            return;
        }

        $builder->where('status', 'active');
    }
}
