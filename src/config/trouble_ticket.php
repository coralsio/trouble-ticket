<?php

return [
    'models' => [
        'troubleTicket' => [
            'presenter' => \Corals\Modules\TroubleTicket\Transformers\TroubleTicketPresenter::class,
            'resource_url' => 'trouble-ticket/trouble-tickets',
            'validation_rules' => [
                'status' => 'required',
                'title' => 'required|max:255',
                'priority' => 'required',
                'estimated_hours' => 'nullable|numeric',
                'description' => 'required',
                'assignee_id' => 'required_without:auto_assignment',
                'model_id' => 'required_with:model_type',
                'owner_id' => 'required_without:is_public_owner',
                'owner_type' => 'required_without:is_public_owner',
                'public_owner.email' => 'required_with:is_public_owner|email',
                'public_owner.name' => 'required_with:is_public_owner|max:255',
                'category_id' => 'required',
//                'team_id' => 'required',
                'issue_type_id' => 'required',
            ],
            'actions' => [
                'updateStatus' => [
                    'icon' => 'fa fa-edit fa-fw',
                    'class' => 'btn btn-primary btn-sm',
                    'href_pattern' => [
                        'pattern' => '[arg]/update-status-modal/[arg]',
                        'replace' => [
                            "return url(config('trouble_ticket.models.troubleTicket.resource_url'));",
                            'return $object->hashed_id;',
                        ],
                    ],
                    'policies' => ['partialUpdate'],
                    'label_pattern' => [
                        'pattern' => '[arg]',
                        'replace' => ['return trans("TroubleTicket::labels.trouble_ticket.update_status");'],
                    ],
                    'data' => [
                        'action' => 'modal-load',
                    ],
                ],
                'reOpen' => [
                    'class' => 'btn btn-danger btn-sm',
                    'href_pattern' => [
                        'pattern' => '[arg]/reopen',
                        'replace' => [
                            'return $object->getShowURL();',
                        ],
                    ],
                    'policies' => ['reOpen'],
                    'label_pattern' => [
                        'pattern' => '[arg]',
                        'replace' => ['return trans("TroubleTicket::labels.trouble_ticket.re_open");'],
                    ],
                    'data' => [
                        'action' => 'post',
                        'table' => '#TroubleTicketsDataTable',
                    ],
                ],
                'resolve' => [
                    'class' => 'btn btn-success btn-sm',
                    'href_pattern' => [
                        'pattern' => '[arg]/resolve',
                        'replace' => [
                            'return $object->getShowURL();',
                        ],
                    ],
                    'policies' => ['resolve'],
                    'label_pattern' => [
                        'pattern' => '[arg]',
                        'replace' => ['return trans("TroubleTicket::labels.trouble_ticket.resolve");'],
                    ],
                    'data' => [
                        'action' => 'post',
                        'table' => '#TroubleTicketsDataTable',
                    ],
                ],
            ],
            'model_field_models' => [
                [
                    'path' => 'ecommerce.models.order',
                    'public' => false,
                    'scopes' => [
                        \Corals\Modules\TroubleTicket\Scopes\EcommerceOrdersScope::class,
                    ],
                ],
                [
                    'path' => 'ecommerce.models.product',
                    'public' => true,
                    'scopes' => [
                        \Corals\Modules\TroubleTicket\Scopes\EcommerceProductsScope::class,
                    ],
                ],
                [
                    'path' => 'marketplace.models.order',
                    'public' => false,
                    'scopes' => [
                        \Corals\Modules\TroubleTicket\Scopes\MarketPlaceOrdersScope::class,
                    ],
                ],
                [
                    'path' => 'marketplace.models.product',
                    'public' => true,
                    'scopes' => [
                        \Corals\Modules\TroubleTicket\Scopes\MarketPlaceProductsScope::class,
                    ],
                ],
                [
                    'path' => 'payment_common.models.invoice',
                    'public' => false,
                    'scopes' => [
                        \Corals\Modules\TroubleTicket\Scopes\InvoicesScope::class,
                    ],
                ],
                [
                    'path' => 'payment_common.models.transaction',
                    'public' => false,
                    'scopes' => [
                        \Corals\Modules\TroubleTicket\Scopes\TransactionsScope::class,
                    ],
                ],
                [
                    'path' => 'classified.models.product',
                    'public' => true,
                    'scopes' => [
                        \Corals\Modules\TroubleTicket\Scopes\ClassifiedProductsScope::class,
                    ],
                ],
                [
                    'path' => 'directory.models.listing',
                    'public' => true,
                    'scopes' => [
                        \Corals\Modules\TroubleTicket\Scopes\DirectoryListingsScope::class,
                    ],
                ],
                [
                    'path' => 'reservation.models.reservation',
                    'public' => true,
                    'scopes' => [
                        \Corals\Modules\TroubleTicket\Scopes\ReservationsScope::class,
                    ],
                ],
                [
                    'path' => 'subscriptions.models.subscription',
                    'public' => false,
                    'scopes' => [
                        \Corals\Modules\TroubleTicket\Scopes\SubscriptionsScope::class,
                    ],
                ],
            ],
        ],
        'team' => [
            'presenter' => \Corals\Modules\TroubleTicket\Transformers\TeamPresenter::class,
            'resource_url' => 'trouble-ticket/teams',
        ],
        'issue_type' => [
            'presenter' => \Corals\Modules\TroubleTicket\Transformers\IssueTypePresenter::class,
            'resource_url' => 'trouble-ticket/issue-types',
        ],
    ],
];
