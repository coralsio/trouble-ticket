<?php

namespace Corals\Modules\TroubleTicket\DataTables;

use Corals\Foundation\DataTables\BaseDataTable;
use Corals\Modules\TroubleTicket\Facades\TroubleTickets;
use Corals\Modules\TroubleTicket\Models\TroubleTicket;
use Corals\Modules\TroubleTicket\Transformers\TroubleTicketTransformer;
use Corals\Modules\Utility\ListOfValue\Facades\ListOfValues;
use Corals\User\Models\User;
use Illuminate\Support\Arr;
use Yajra\DataTables\EloquentDataTable;

class TroubleTicketsDataTable extends BaseDataTable
{
    protected $usesQueryBuilderFilters = true;

    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $this->setResourceUrl(config('trouble_ticket.models.troubleTicket.resource_url'));

        $dataTable = new EloquentDataTable($query);

        return $dataTable->setTransformer(new TroubleTicketTransformer());
    }

    /**
     * Get query source of dataTable.
     * @param TroubleTicket $model
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function query(TroubleTicket $model)
    {
        $query = $model->newQuery();

        if (!user()->hasPermissionTo('TroubleTicket::troubleTicket.view')) {
            $query->where('owner_id', user()->id)->where('owner_type', getMorphAlias(User::class));
        }

        return $query;
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        $columns = [
            'id' => ['visible' => false],
            'code' => ['title' => trans('TroubleTicket::attributes.troubleTicket.code')],
            'title' => ['title' => trans('TroubleTicket::attributes.troubleTicket.title')],
            'status' => ['title' => trans('TroubleTicket::attributes.troubleTicket.status')],
            'owner' => [
                'title' => trans('TroubleTicket::attributes.troubleTicket.owner'),
                'orderable' => false,
                'searchable' => false,
            ],
            'issue_type' => [
                'title' => trans('TroubleTicket::attributes.troubleTicket.issue_type'),
                'orderable' => false,
                'searchable' => false,
            ],
//            'team' => [
//                'title' => trans('TroubleTicket::attributes.troubleTicket.team'),
//                'orderable' => false,
//                'searchable' => false,
//            ],
            'assignee' => [
                'title' => trans('TroubleTicket::attributes.troubleTicket.assignee_id'),
                'orderable' => false,
                'searchable' => false,
            ],
            'model' => [
                'title' => trans('TroubleTicket::attributes.troubleTicket.model'),
                'orderable' => false,
                'searchable' => false,
            ],
//            'category' => [
//                'title' => trans('TroubleTicket::attributes.troubleTicket.category'),
//                'orderable' => false,
//                'searchable' => false,
//            ],
            'priority' => ['title' => trans('TroubleTicket::attributes.troubleTicket.priority')],
//            'due_date' => ['title' => trans('TroubleTicket::attributes.troubleTicket.due_date')],
            'closed_at' => ['title' => trans('TroubleTicket::attributes.troubleTicket.closed_at')],
            'updated_at' => ['title' => trans('Corals::attributes.updated_at')],
        ];

        if (!user()->hasPermissionTo('TroubleTicket::troubleTicket.view')) {
            unset($columns['owner']);
        }

        return $columns;
    }

    protected function getExtraScripts()
    {
        return 'updateParentObjectField(' . json_encode(get_models('trouble_ticket.models.troubleTicket.model_field_models')) . ', $("#model_type"), $("[name=\'model_id\']"));';
    }

    public function getFilters()
    {
        $filters = [
            'code' => [
                'title' => trans('TroubleTicket::attributes.troubleTicket.code'),
                'class' => 'col-md-2',
                'type' => 'text',
                'condition' => 'like',
                'active' => true
            ],
            'title' => [
                'title' => trans('TroubleTicket::attributes.troubleTicket.title'),
                'class' => 'col-md-2',
                'type' => 'text',
                'condition' => 'like',
                'active' => true
            ],
            'status' => [
                'title' => trans('TroubleTicket::attributes.troubleTicket.status'),
                'class' => 'col-md-2',
                'type' => 'select2',
                'options' => ListOfValues::get('tt_status'),
                'active' => true
            ],
            'priority' => [
                'title' => trans('TroubleTicket::attributes.troubleTicket.priority'),
                'class' => 'col-md-2',
                'type' => 'select2',
                'options' => ListOfValues::get('tt_priority'),
                'active' => true
            ],
            'due_date' => [
                'title' => trans('TroubleTicket::attributes.troubleTicket.due_date'),
                'class' => 'col-md-4',
                'type' => 'date_range',
                'active' => true
            ],
            'closed_at' => [
                'title' => trans('TroubleTicket::attributes.troubleTicket.closed_at'),
                'class' => 'col-md-4',
                'type' => 'date_range',
                'active' => true
            ],
            'model_type' => [
                'title' => trans('TroubleTicket::attributes.troubleTicket.model_type'),
                'class' => 'col-md-2',
                'type' => 'select2',
                'options' => \Arr::pluck(get_models('trouble_ticket.models.troubleTicket.model_field_models'), 'label', 'model_class'),
                'active' => true
            ],

            'model_id' => [
                'title' => trans('TroubleTicket::attributes.troubleTicket.model'),
                'class' => 'col-md-2',
                'type' => 'select2-ajax',
                'model' => '',
                'columns' => [],
                'active' => true
            ],

            'owner_id' => [
                'title' => trans('TroubleTicket::attributes.troubleTicket.owner'),
                'class' => 'col-md-2',
                'type' => 'select2-ajax',
                'model' => User::class,
                'columns' => ['email', 'name', 'last_name'],
                'active' => true
            ],
            'assignees.assignee_id' => [
                'title' => trans('TroubleTicket::attributes.troubleTicket.assignee_id'),
                'class' => 'col-md-2',
                'type' => 'select2-ajax',
                'model' => User::class,
                'columns' => ['email', 'name', 'last_name'],
                'active' => true
            ],
            'issue_type_id' => [
                'title' => trans('TroubleTicket::attributes.troubleTicket.issue_type'),
                'class' => 'col-md-2',
                'type' => 'select2',
                'options' => TroubleTickets::getIssuesList(),
                'active' => true
            ],
            'team_id' => [
                'title' => trans('TroubleTicket::attributes.troubleTicket.team'),
                'class' => 'col-md-2',
                'type' => 'select2',
                'options' => TroubleTickets::getTeamsList(),
                'active' => true
            ],
            'category_id' => [
                'title' => trans('TroubleTicket::attributes.troubleTicket.category'),
                'class' => 'col-md-2',
                'type' => 'select2',
                'options' => \Category::getCategoriesByParent('tt-categories'),
                'active' => true
            ],
//            'archived' => [
//                'title' => trans('TroubleTicket::attributes.troubleTicket.archived'),
//                'class' => 'col-md-2',
//                'type' => 'boolean',
//                'active' => true
//            ],
        ];

        if (!user()->hasPermissionTo('TroubleTicket::troubleTicket.view')) {
            $filters = Arr::except($filters, ['owner_id', 'owner_type', 'closed_at', 'due_date', 'team_id']);
        }

        return $filters;
    }

    protected function getBulkActions()
    {
        return [
            'update_status' => [
                'title' => sprintf('<i class="fa fa-edit fa-fw"></i> %s',
                    __('TroubleTicket::labels.trouble_ticket.update_status')),
                'permission' => 'TroubleTicket::troubleTicket.update',
                'action' => 'modal-load',
                'href' => url(config('trouble_ticket.models.troubleTicket.resource_url') . '/update-status-modal'),
                'modal-title' => __('TroubleTicket::labels.trouble_ticket.update_status'),
                'confirmation' => __('TroubleTicket::labels.trouble_ticket.want_to_delete'),
            ],
            'delete' => [
                'title' => trans('Corals::labels.delete'),
                'permission' => 'TroubleTicket::troubleTicket.delete',
                'confirmation' => __('TroubleTicket::labels.trouble_ticket.want_to_delete'),
            ],
            'archive' => [
                'title' => __('TroubleTicket::labels.trouble_ticket.archive'),
                'permission' => 'TroubleTicket::troubleTicket.update',
                'confirmation' => __('TroubleTicket::labels.trouble_ticket.archive_confirmation'),
            ],
            'unarchive' => [
                'title' => __('TroubleTicket::labels.trouble_ticket.unarchive'),
                'permission' => 'TroubleTicket::troubleTicket.update',
                'confirmation' => __('TroubleTicket::labels.trouble_ticket.unarchive_confirmation'),
            ],
        ];
    }

    protected function getOptions()
    {
        $url = url(config('trouble_ticket.models.troubleTicket.resource_url'));
        return ['resource_url' => $url];
    }
}
