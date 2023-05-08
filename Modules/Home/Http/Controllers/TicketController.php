<?php

namespace Modules\Home\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Home\Models\Ticket;
use Modules\Home\Models\Campaign;
use Illuminate\Routing\Controller;
use App\Repositories\TicketRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Support\Renderable;

class TicketController extends Controller
{
    protected $ticketRepository;

    public function __construct(TicketRepository $ticketRepository)
    {
        $this->ticketRepository = $ticketRepository;
    }

    /**
     * Use ticket
     *
     * @param Request $request
     * @param Ticket $ticket
     * @return json
     */
    public function useTicket(Request $request, Ticket $ticket)
    {
        $validator = Validator::make($request->all(), [
            'campaignId' => 'required',
            'code'       => 'required'
        ]);

        if($validator->fails()) {
            return response()->json(['message' => 'Invalid request input'], 400);
        }

        $ticket->setToModel($request->only('code'));
        $ticket->setParent(
            new Campaign($request->campaignId)
        );

        if(! $this->ticketRepository->useTicket($ticket))
        {
            return response()->json(['message' => 'Ticket already used or invalid'], 400);
        }

        return response()->json(['message' => 'success using ticket']);
    }
}
