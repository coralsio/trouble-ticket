<?php

namespace Corals\Modules\TroubleTicket\DataTables;

use Corals\Foundation\DataTables\BaseDataTable;
use Corals\Modules\TroubleTicket\Models\Team;
use Corals\Modules\TroubleTicket\Transformers\TeamTransformer;
use Yajra\DataTables\EloquentDataTable;

class TeamsDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $this->setResourceUrl(config('trouble_ticket.models.team.resource_url'));

        $dataTable = new EloquentDataTable($query);

        return $dataTable->setTransformer(new TeamTransformer());
    }

    /**
     * Get query source of dataTable.
     * @param Team $model
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function query(Team $model)
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
            'name' => ['title' => trans('TroubleTicket::attributes.team.name')],
            'users' => [
                'title' => trans('TroubleTicket::attributes.team.users'),
                'orderable' => false,
                'searchable' => false,
            ],
            'email' => [
                'title' => trans('TroubleTicket::attributes.team.email'),
                'orderable' => false,
                'searchable' => false,
            ],
            'slack' => [
                'title' => trans('TroubleTicket::attributes.team.slack'),
                'orderable' => false,
                'searchable' => false,
            ],
            'created_at' => ['title' => trans('Corals::attributes.created_at')],
            'updated_at' => ['title' => trans('Corals::attributes.updated_at')],
        ];
    }
}
