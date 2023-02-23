<?php

namespace Corals\Modules\TroubleTicket\Transformers;

use Corals\Foundation\Transformers\BaseTransformer;
use Corals\Modules\TroubleTicket\Models\Team;
use Illuminate\Support\Str;

class TeamTransformer extends BaseTransformer
{
    public function __construct($extras = [])
    {
        $this->resource_url = config('trouble_ticket.models.team.resource_url');

        parent::__construct($extras);
    }

    /**
     * @param Team $team
     * @return array
     * @throws \Throwable
     */
    public function transform(Team $team)
    {
        $users = [];

        $team->users->each(function ($assignee) use (&$users) {
            $users[] = $assignee->assignee->full_name;
        });

        $transformedArray = [
            'id' => $team->id,
            'name' => user() && user()->can('view', $team) ? sprintf(
                '<a href="%s">%s</a>',
                $team->getShowURL(),
                $team->name
            ) : $team->name,
            'email' => Str::limit($team->getProperty('notifications_channels.email')) ?? '-',
            'slack' => Str::limit($team->getProperty('notifications_channels.slack'), 50) ?? '-',
            'users' => formatArrayAsLabels($users, 'primary'),
            'created_at' => format_date($team->created_at),
            'updated_at' => format_date($team->updated_at),
            'action' => $this->actions($team),
        ];

        return parent::transformResponse($transformedArray);
    }
}
