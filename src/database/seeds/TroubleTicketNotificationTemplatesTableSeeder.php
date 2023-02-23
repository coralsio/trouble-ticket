<?php

namespace Corals\Modules\TroubleTicket\database\seeds;

use Illuminate\Database\Seeder;

class TroubleTicketNotificationTemplatesTableSeeder extends Seeder
{
    public function run()
    {
        \DB::table('notification_templates')->insert([
            [
                'name' => 'notifications.trouble_tickets.status_changed',
                'title' => '{tt_code} Support Ticket status has been changed to {new_status}',
                'friendly_name' => 'Support Ticket status changed',
                'body' => '{"mail":"Hi {owner},<br/> you received this email because {tt_code} Support Ticket status has been changed from {old_status} to {new_status}"}',
                'extras' => '[]',
                'via' => '["mail"]',
            ],
            [
                'name' => 'notifications.trouble_tickets.assignment',
                'title' => 'You have been assigned to {tt_code} Support Ticket',
                'friendly_name' => 'Support Ticket assignment',
                'body' => '{"mail": "hi {assignee},<br/> you received this email because you have been assigned to <a href=\"{tt_show_url}\">{tt_code} Support Ticket</a>"}',
                'extras' => '[]',
                'via' => '["mail"]',
            ],
        ]);
    }
}
