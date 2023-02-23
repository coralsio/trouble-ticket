<?php

return [
    'troubleTicket' => [
        'code' => 'Code',
        'title' => 'Title',
        'description' => 'Description',
        'status' => 'Status',
        'priority' => 'Priority',
        'due_date' => 'Due Date',
        'closed_at' => 'Closed At',
        'estimated_hours' => 'Estimated Hours',
        'archived' => 'Archived',
        'auto_assignment' => 'Auto Assignment',
        'assignee_id' => 'Assignee',
        'model_type' => 'Belongs To',
        'model' => 'Item',
        'owner' => 'Owner',
        'owner_type' => 'Owner Type',
        'is_public' => 'Is Public',
        'properties' => 'Properties',
        'public_owner' => [
            'email' => 'Email',
            'name' => 'Name',
            'email_public' => 'Your Email',
            'name_public' => 'Your Name',
        ],
        'category' => 'Category',
        'issue_type' => 'Issue Type',
        'team' => 'Team',
    ],
    'team' => [
        'name' => 'Name',
        'email' => 'Email',
        'slack' => 'Slack',
        'users' => 'Users',
    ],
    'issue_type' => [
        'title' => 'Title',
        'description' => 'Description',
        'team' => 'Team',
        'categories' => 'Categories',
        'solutions' => [
            'title' => 'Title',
            'order' => 'Order',
            'details' => 'Details',
        ],
    ],
];
