<?php

namespace Corals\Modules\TroubleTicket\Scopes;

use Corals\Foundation\Contracts\CoralsScope;

class InvoicesScope implements CoralsScope
{
    public function apply($builder, $extras = [])
    {
        if (user()->hasPermissionTo('Administrations::admin.payment')
            || user()->hasPermissionTo('Payment::invoices.view_all')) {
            return;
        }

        $builder->where('user_id', user()->id);
    }
}
