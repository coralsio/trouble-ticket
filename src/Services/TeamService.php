<?php

namespace Corals\Modules\TroubleTicket\Services;

use Corals\Foundation\Services\BaseServiceClass;
use Corals\User\Models\User;

class TeamService extends BaseServiceClass
{
    protected $excludedRequestParams = ['users'];

    public function postStoreUpdate($request, &$additionalData)
    {
        $this->model->users()->delete();

        foreach ($request->get('users', []) as $userId) {
            if (! $userId) {
                continue;
            }

            $userAssigneeRecords[] = [
                'assignee_id' => $userId,
                'assignee_type' => getModelMorphMap(User::class),
            ];
        }


        if (isset($userAssigneeRecords)) {
            $this->model->users()->createMany($userAssigneeRecords);
        }
    }
}
