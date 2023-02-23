<?php

namespace Corals\Modules\TroubleTicket\Scopes;

use Corals\Foundation\Contracts\CoralsScope;

class EcommerceOrdersScope implements CoralsScope
{
    public function apply($builder, $extras = [])
    {
        if (user()->hasPermissionTo('Administrations::admin.ecommerce')) {
            return;
        }

        $builder->where('user_id', user()->id);
    }
}
