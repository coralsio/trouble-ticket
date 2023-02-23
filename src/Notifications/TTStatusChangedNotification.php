<?php

namespace Corals\Modules\TroubleTicket\Notifications;


use Corals\User\Communication\Classes\CoralsBaseNotification;

class TTStatusChangedNotification extends CoralsBaseNotification
{

    /**
     * @return mixed
     */
    public function getNotifiables()
    {
        return [
            $this->data['owner']
        ];
    }

    public function getOnDemandNotificationNotifiables()
    {
        return [
            'mail' => data_get($this->data['public_owner'], 'email')
        ];
    }

    public function getNotificationMessageParameters($notifiable, $channel)
    {

        $owner = $this->data['owner'];
        $troubleTicket = $this->data['trouble_ticket'];

        if ($owner) {
            $ownerIdentifier = $owner->presentStripTags('identifier');
        } else {
            $ownerIdentifier = data_get($this->data['public_owner'], 'name');
        }

        return [
            'owner' => $ownerIdentifier,
            'tt_code' => $troubleTicket->code,
            'new_status' => $this->data['new_status'],
            'old_status' => $this->data['old_status']
        ];
    }


    public static function getNotificationMessageParametersDescriptions()
    {
        return [
            'owner' => 'TT Owner',
            'tt_code' => 'TT code',
            'new_status' => 'New status',
            'old_status' => 'Old status'
        ];
    }
}
