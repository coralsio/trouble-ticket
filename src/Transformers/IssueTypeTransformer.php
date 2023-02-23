<?php

namespace Corals\Modules\TroubleTicket\Transformers;

use Corals\Foundation\Transformers\BaseTransformer;
use Corals\Modules\TroubleTicket\Facades\TroubleTickets;
use Corals\Modules\TroubleTicket\Models\IssueType;
use Illuminate\Support\Str;

class IssueTypeTransformer extends BaseTransformer
{
    public function __construct($extras = [])
    {
        $this->resource_url = config('trouble_ticket.models.issue_type.resource_url');

        parent::__construct($extras);
    }

    /**
     * @param IssueType $issueType
     * @return array
     * @throws \Throwable
     */
    public function transform(IssueType $issueType)
    {
        $transformedArray = [
            'id' => $issueType->id,
            'title' => user() && user()->can('view', $issueType) ? sprintf(
                '<a href="%s">%s</a>',
                $issueType->getShowURL(),
                $issueType->title
            ) : $issueType->title,
            'description' => strlen($issueType->description) > 100 ? generatePopover(Str::limit(
                $issueType->description,
                100
            )) : $issueType->description,
            'solutions' => $this->formatSolutionsTable(TroubleTickets::getSortedIssueTypeSolutions($issueType)),
            'team' => $issueType->team->present('name'),
            'categories' => formatArrayAsLabels($issueType->categories()->pluck('name'), 'info'),
            'created_at' => format_date($issueType->created_at),
            'updated_at' => format_date($issueType->updated_at),
            'action' => $this->actions($issueType),
        ];

        return parent::transformResponse($transformedArray);
    }

    /**
     * @param $solutions
     * @return string
     */
    protected function formatSolutionsTable($solutions): string
    {
        if (! $solutions) {
            return '';
        }

        $header = sprintf(
            "<thead><th>%s</th><th>%s</th><th>%s</th></thead>",
            '#',
            trans('TroubleTicket::attributes.issue_type.solutions.title'),
            trans('TroubleTicket::attributes.issue_type.solutions.details')
        );

        $body = '';

        foreach ($solutions as $solution) {
            $body .= sprintf(
                "<tr><td>%s</td><td>%s</td><td>%s</td></tr>",
                data_get($solution, 'order'),
                data_get($solution, 'title'),
                data_get($solution, 'details')
            );
        }

        return sprintf("<table class='table table-striped'>%s %s</table>", $header, $body);
    }
}
