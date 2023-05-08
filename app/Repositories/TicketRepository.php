<?php

namespace App\Repositories;

use Modules\Home\Models\Ticket;
use App\Repositories\BaseRepository;

class TicketRepository extends BaseRepository
{
    public function useTicket(Ticket $ticket)
    {
        $collection = $this->from($ticket)
            ->where('code', '=', $ticket->code)
            ->where('status', '=', Ticket::STATUS_OPEN)
            ->first();

        if(! $collection) {
            return false;
        }

        $dataSaved = $this->set([
            'status' => Ticket::STATUS_CLOSE,
            'updatedAt' => $this->getTimestamp()
        ]);

        if(! $dataSaved) {
            return false;
        }

        return true;
    }
}
