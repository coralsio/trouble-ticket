<?php

namespace Corals\Modules\TroubleTicket\Policies;

use Corals\Foundation\Policies\BasePolicy;
use Corals\Modules\TroubleTicket\Models\IssueType;
use Corals\User\Models\User;

class IssueTypePolicy extends BasePolicy
{
    protected $administrationPermission = 'Administrations::admin.trouble_ticket';

    /**
     * @param User $user
     * @return bool
     */
    public function view(User $user)
    {
        return $user->can('TroubleTicket::issue_type.view');
    }

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->can('TroubleTicket::issue_type.create');
    }

    /**
     * @param User $user
     * @param IssueType $issueType
     * @return bool
     */
    public function update(User $user, IssueType $issueType)
    {
        return $user->can('TroubleTicket::issue_type.update');
    }

    /**
     * @param User $user
     * @param IssueType $issueType
     * @return bool
     */
    public function destroy(User $user, IssueType $issueType)
    {
        return $user->can('TroubleTicket::issue_type.delete');
    }
}
