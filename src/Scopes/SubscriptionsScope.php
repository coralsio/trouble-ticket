<?php

namespace Corals\Modules\TroubleTicket\Scopes;

use Corals\Foundation\Contracts\CoralsScope;

class SubscriptionsScope implements CoralsScope
{
    public function apply($builder, $extras = [])
    {
        if (user()->hasPermissionTo('Administrations::admin.subscription')) {
            return;
        }

        $builder->where('user_id', user()->id);
    }
}
