<?php

namespace Corals\Modules\TroubleTicket\Scopes;

use Corals\Foundation\Contracts\CoralsScope;
use Corals\User\Models\User;

class TransactionsScope implements CoralsScope
{
    public function apply($builder, $extras = [])
    {
        if (user()->hasPermissionTo('Administrations::admin.payment')
            || user()->hasPermissionTo('Payment::transaction.view_all')) {
            return;
        }

        $builder->where('owner_id', user()->id)
            ->where('owner_type', getMorphAlias(User::class));
    }
}
