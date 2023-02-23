<?php

namespace Corals\Modules\TroubleTicket\Scopes;

use Corals\Foundation\Contracts\CoralsScope;

class ReservationsScope implements CoralsScope
{

    public function apply($builder, $extras = [])
    {
        if (user() && user()->hasPermissionTo('Administrations::admin.reservation')) {
            return;
        }

        $builder->where('owner_type', 'User')
            ->where('owner_id', optional(user())->id);
    }
}
