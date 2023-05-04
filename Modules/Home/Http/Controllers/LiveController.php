<?php

namespace Modules\Home\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Home\Models\LiveStreaming;
use App\Repositories\LiveStreamingRepository;

class LiveController extends Controller
{
    /**
     * @var LiveStreamingRepository
     */
    protected $liveStreamingRepository;

    public function __construct(LiveStreamingRepository $liveStreamingRepository)
    {
        $this->liveStreamingRepository = $liveStreamingRepository;
    }

    /**
     * Find info from repository.
     *
     * @return void
     */
    public function getOnAirLive(Request $request, LiveStreaming $liveStreaming)
    {
        $live = $this->liveStreamingRepository->from($liveStreaming)
        ->where('onAir', '=', true)->first();

        dd($live);
        // if (! $result) {
        //     abort(404);
        // }

        // echo $result->title;
    }
}
