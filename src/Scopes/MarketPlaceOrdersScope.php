<?php

namespace Corals\Modules\TroubleTicket\Scopes;

use Corals\Foundation\Contracts\CoralsScope;
use Corals\Modules\Marketplace\Facades\Store;

class MarketPlaceOrdersScope implements CoralsScope
{
    public function apply($builder, $extras = [])
    {
        if (user()->hasPermissionTo('Administrations::admin.marketplace')) {
            return;
        }

        if (user()->hasRole('vendor')) {
            $store = Store::getVendorStore();
            $builder->where('store_id', optional($store)->id);

            return;
        }

        $builder->where('user_id', user()->id);
    }
}
