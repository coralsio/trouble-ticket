<?php

namespace Corals\Modules\TroubleTicket\Policies;

use Corals\Foundation\Policies\BasePolicy;
use Corals\Modules\TroubleTicket\Models\Team;
use Corals\User\Models\User;

class TeamPolicy extends BasePolicy
{
    protected $administrationPermission = 'Administrations::admin.trouble_ticket';

    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user)
    {
        return $user->can('TroubleTicket::team.view');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->can('TroubleTicket::team.create');
    }

    /**
     * @param User $user
     * @param Team $team
     * @return bool
     */
    public function update(User $user, Team $team)
    {
        return $user->can('TroubleTicket::team.update');
    }

    /**
     * @param User $user
     * @param Team $team
     * @return bool
     */
    public function destroy(User $user, Team $team)
    {
        return $user->can('TroubleTicket::team.delete');
    }
}
