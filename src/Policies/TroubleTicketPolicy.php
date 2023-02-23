<?php

namespace Corals\Modules\TroubleTicket\Policies;

use Corals\Foundation\Policies\BasePolicy;
use Corals\Modules\TroubleTicket\Models\TroubleTicket;
use Corals\User\Models\User;

class TroubleTicketPolicy extends BasePolicy
{
    protected $administrationPermission = 'Administrations::admin.trouble_ticket';

    protected $skippedAbilities = ['partialUpdate', 'comment', 'reOpen', 'resolve'];

    /**
     * @param User $user
     * @param TroubleTicket|null $troubleTicket
     * @return bool
     */
    public function view(User $user, TroubleTicket $troubleTicket = null)
    {
        $view = $user->can('TroubleTicket::troubleTicket.view');
        
        if ($troubleTicket && !$view) {
            return $this->isOwner($user, $troubleTicket) && $user->can('TroubleTicket::troubleTicket.view_my_tickets');
        } else {
            return $view || $user->can('TroubleTicket::troubleTicket.view_my_tickets');
        }
    }

    public function fullCreate(User $user)
    {
        return $user->can($this->administrationPermission);
    }

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->can('TroubleTicket::troubleTicket.create');
    }

    /**
     * @param User $user
     * @param TroubleTicket $troubleTicket
     * @return bool
     */
    public function partialUpdate(User $user, TroubleTicket $troubleTicket)
    {
        return !in_array($troubleTicket->status, TroubleTicket::LOCKED_STATUSES)
            && ($user->can('TroubleTicket::troubleTicket.update') || $user->can($this->administrationPermission));
    }

    /**
     * @param User $user
     * @param TroubleTicket|null $troubleTicket
     * @return bool
     */
    public function update(User $user, TroubleTicket $troubleTicket = null)
    {
        return $user->can($this->administrationPermission);
    }

    /**
     * @param User $user
     * @param TroubleTicket $troubleTicket
     * @return bool
     */
    public function destroy(User $user, TroubleTicket $troubleTicket)
    {
        return $user->can('TroubleTicket::troubleTicket.delete');
    }

    protected function isOwner(User $user, TroubleTicket $troubleTicket)
    {
        return $troubleTicket->owner_type == getMorphAlias(User::class) && $user->id == $troubleTicket->owner_id;
    }

    /**
     * @param User $user
     * @param TroubleTicket $troubleTicket
     * @return bool
     *
     */
    public function comment(User $user, TroubleTicket $troubleTicket)
    {
        if ($user->cannot('TroubleTicket::troubleTicket.comment')) {
            return false;
        }

        return !in_array($troubleTicket->status, TroubleTicket::LOCKED_STATUSES)
            && ($this->isOwner($user, $troubleTicket)
                || $user->can('TroubleTicket::troubleTicket.update')
                || $user->can($this->administrationPermission));
    }

    /**
     * @param User $user
     * @param TroubleTicket $troubleTicket
     * @return bool
     */
    public function reOpen(User $user, TroubleTicket $troubleTicket)
    {
        return in_array($troubleTicket->status, TroubleTicket::LOCKED_STATUSES);
    }

    public function resolve(User $user, TroubleTicket $troubleTicket)
    {
        return !in_array($troubleTicket->status, ['resolved']);
    }
}
