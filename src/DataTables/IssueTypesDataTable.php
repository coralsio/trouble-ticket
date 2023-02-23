<?php

namespace Corals\Modules\TroubleTicket\DataTables;

use Corals\Foundation\DataTables\BaseDataTable;
use Corals\Modules\TroubleTicket\Models\IssueType;
use Corals\Modules\TroubleTicket\Transformers\IssueTypeTransformer;
use Yajra\DataTables\EloquentDataTable;

class IssueTypesDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $this->setResourceUrl(config('trouble_ticket.models.issue_type.resource_url'));

        $dataTable = new EloquentDataTable($query);

        return $dataTable->setTransformer(new IssueTypeTransformer());
    }

    /**
     * Get query source of dataTable.
     * @param IssueType $model
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function query(IssueType $model)
    {
        return $model->newQuery();
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'id' => ['visible' => false],
            'title' => ['title' => trans('TroubleTicket::attributes.issue_type.title')],
            'description' => ['title' => trans('TroubleTicket::attributes.issue_type.description')],
            'team' => [
                'title' => trans('TroubleTicket::attributes.issue_type.team'),
                'orderable' => false,
                'searchable' => false
            ],
            'categories' => [
                'title' => trans('TroubleTicket::attributes.issue_type.categories'),
                'orderable' => false,
                'searchable' => false
            ],
            'updated_at' => ['title' => trans('Corals::attributes.updated_at')],
        ];
    }
}
