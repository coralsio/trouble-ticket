<?php

namespace Corals\Modules\TroubleTicket\Observers;

use Corals\Modules\TroubleTicket\Models\TroubleTicket;

class TroubleTicketObserver
{
    /**
     * @param TroubleTicket $troubleTicket
     */
    public function created(TroubleTicket $troubleTicket)
    {
    }

    public function updating(TroubleTicket $troubleTicket)
    {
        if ($troubleTicket->isDirty(['status'])) {
            $oldValue = $troubleTicket->getOriginal('status');
            $newValue = $troubleTicket->status;
            $this->sendStatusChangedNotification($troubleTicket, $oldValue, $newValue);

            if ($newValue == 'closed') {
                $troubleTicket->closed_at = now();
            }
        }
    }

    /**
     * @param $troubleTicket
     * @param $oldValue
     * @param $newValue
     */
    public function sendStatusChangedNotification($troubleTicket, $oldValue, $newValue): void
    {
        event('notifications.trouble_tickets.status_changed', [
            'trouble_ticket' => $troubleTicket,
            'owner' => $troubleTicket->owner,
            'new_status' => $newValue,
            'old_status' => $oldValue,
            'public_owner' => $troubleTicket->public_owner,
        ]);
    }
}
