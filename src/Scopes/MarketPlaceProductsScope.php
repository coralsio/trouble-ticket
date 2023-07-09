<?php

namespace Corals\Modules\TroubleTicket\Scopes;

use Corals\Foundation\Contracts\CoralsScope;

class MarketPlaceProductsScope implements CoralsScope
{
    public function apply($builder, $extras = [])
    {
        if (user() && user()->hasPermissionTo('Administrations::admin.marketplace')) {
            return;
        }

        //        if (user() && user()->hasRole('vendor')) {
        //            $store = Store::getVendorStore();
        //            $builder->where('store_id', optional($store)->id);
        //            return;
        //        }

        $builder->where('status', 'active');
    }
}
