<?php

namespace Corals\Modules\TroubleTicket\Notifications;


use Corals\User\Communication\Classes\CoralsBaseNotification;

class TTAssignmentNotification extends CoralsBaseNotification
{

    /**
     * @return mixed
     */
    public function getNotifiables()
    {
        return [
            $this->data['assignee']
        ];
    }

    public function getNotificationMessageParameters($notifiable, $channel)
    {
        $assignee = $this->data['assignee'];

        $troubleTicket = $this->data['trouble_ticket'];

        return [
            'assignee' => $assignee->presentStripTags('identifier'),
            'tt_code' => $troubleTicket->code,
            'tt_show_url' => $troubleTicket->getShowURL()
        ];
    }


    public static function getNotificationMessageParametersDescriptions()
    {
        return [
            'assignee' => 'Assignee',
            'tt_code' => 'Support Ticket code',
            'tt_show_url' => 'Support Ticket Show URL'
        ];
    }
}
