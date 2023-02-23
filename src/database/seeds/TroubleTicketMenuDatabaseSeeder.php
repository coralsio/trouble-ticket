<?php

namespace Corals\Modules\TroubleTicket\database\seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TroubleTicketMenuDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('menus')->insert([
            'parent_id' => 1,// admin
            'key' => 'myTroubleTicket',
            'url' => config('trouble_ticket.models.troubleTicket.resource_url'),
            'active_menu_url' => config('trouble_ticket.models.troubleTicket.resource_url') . '*',
            'name' => 'My Support Tickets',
            'description' => 'Support Ticket Menu Item',
            'icon' => 'fa fa-ticket',
            'target' => null,
            'roles' => '["2"]',
            'order' => 0,
        ]);

        $troubleTicket_menu_id = DB::table('menus')->insertGetId([
            'parent_id' => 1,// admin
            'key' => 'troubleTicket',
            'url' => null,
            'active_menu_url' => 'trouble-ticket*',
            'name' => 'Support Tickets',
            'description' => 'Support Ticket Menu Item',
            'icon' => 'fa fa-ticket',
            'target' => null,
            'roles' => '["1"]',
            'order' => 0,
        ]);


        DB::table('menus')->insert([
            [
                'parent_id' => $troubleTicket_menu_id,
                'key' => null,
                'url' => config('trouble_ticket.models.troubleTicket.resource_url'),
                'active_menu_url' => config('trouble_ticket.models.troubleTicket.resource_url') . '*',
                'name' => 'Support Tickets',
                'description' => 'Support Ticket Menu Item',
                'icon' => 'fa fa-ticket',
                'target' => null,
                'roles' => '["1"]',
                'order' => 0,
            ],
            [
                'parent_id' => $troubleTicket_menu_id,
                'key' => null,
                'url' => config('trouble_ticket.models.team.resource_url'),
                'active_menu_url' => config('trouble_ticket.models.team.resource_url') . '*',
                'name' => 'Teams',
                'description' => 'Support Tickets Teams List',
                'icon' => 'fa fa-users',
                'target' => null,
                'roles' => '["1"]',
                'order' => 0,
            ],
            [
                'parent_id' => $troubleTicket_menu_id,
                'key' => null,
                'url' => config('trouble_ticket.models.issue_type.resource_url'),
                'active_menu_url' => config('trouble_ticket.models.issue_type.resource_url') . '*',
                'name' => 'Issue Types',
                'description' => 'Support Tickets Issue Types List',
                'icon' => 'fa fa-bolt',
                'target' => null,
                'roles' => '["1"]',
                'order' => 0,
            ],
        ]);
    }
}
