<?php

namespace Corals\Modules\TroubleTicket\Transformers;

use Corals\Foundation\Transformers\BaseTransformer;
use Corals\Modules\TroubleTicket\Models\TroubleTicket;
use Corals\Modules\Utility\ListOfValue\Facades\ListOfValues;

class TroubleTicketTransformer extends BaseTransformer
{
    public function __construct($extras = [])
    {
        $this->resource_url = config('trouble_ticket.models.troubleTicket.resource_url');

        parent::__construct($extras);
    }

    /**
     * @param TroubleTicket $troubleTicket
     * @return array
     * @throws \Throwable
     */
    public function transform(TroubleTicket $troubleTicket)
    {
        $assignee = \TroubleTickets::getFirstTTAssignee($troubleTicket);

        $model = $troubleTicket->model;
        $owner = $troubleTicket->owner;

        $transformedArray = [
            'id' => $troubleTicket->id,
            'checkbox' => $this->generateCheckboxElement($troubleTicket),
            'code' => user() ? sprintf("<a href='%s'>%s</a>", $troubleTicket->getShowURL(),
                $troubleTicket->code) : $troubleTicket->code,
            'title' => $troubleTicket->title ?? '-',
            'status' => ListOfValues::getColoredLOVByCode($troubleTicket->status,
                    'tt_status') ?? $troubleTicket->status,
            'priority' => ListOfValues::getColoredLOVByCode($troubleTicket->priority, 'tt_priority') ?? '-',
            'model' => $this->generateShowLink($model),
            'category' => formatStatusAsLabels(optional($troubleTicket->category)->name, ['level' => 'info']),
            'issue_type' => $troubleTicket->issueType ? $troubleTicket->issueType->present('title') : '-',
            'solutions' => $troubleTicket->issueType ? $troubleTicket->issueType->present('solutions') : null,
            'team' => $troubleTicket->team ? $troubleTicket->team->present('name') : '-',
            'assignee' => $this->generateShowLink($assignee),
            'owner' => $this->generateShowLink($owner),
            'is_public' => yesNoFormatter($troubleTicket->isPublic),
            'estimated_hours' => $troubleTicket->estimated_hours ? trans_choice('TroubleTicket::labels.trouble_ticket.estimated_hours',
                $troubleTicket->estimated_hours, ['hours' => $troubleTicket->estimated_hours]) : '-',
            'due_date' => format_date_time($troubleTicket->due_date) ?? '-',
            'closed_at' => format_date_time($troubleTicket->closed_at) ?? '-',
            'archived' => yesNoFormatter($troubleTicket->archived),
            'created_at' => format_date_time($troubleTicket->created_at),
            'updated_at' => format_date_time($troubleTicket->updated_at),
            'action' => $this->actions($troubleTicket)
        ];

        return parent::transformResponse($transformedArray);
    }

    /**
     * @param $object
     * @return string
     */
    protected function generateShowLink($object): string
    {
        if ($object && $object->id) {
            return user() && user()->can('view', $object) ? sprintf("<a href='%s'>%s</a>", $object->getShowURL(),
                $object->getIdentifier()) : $object->getIdentifier();
        }

        return '-';
    }
}
