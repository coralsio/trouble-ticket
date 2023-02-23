<?php

namespace Corals\Modules\TroubleTicket\Scopes;

use Corals\Foundation\Contracts\CoralsScope;

class UsersScope implements CoralsScope
{
    public function apply($builder, $extras = [])
    {
        return $builder->whereHas('roles', function ($roles) {
            $roles->where('roles.name', '<>', 'superuser');
        });
    }
}
