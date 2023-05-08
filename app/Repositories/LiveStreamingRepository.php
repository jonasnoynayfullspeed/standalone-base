<?php

namespace App\Repositories;

use App\Services\FirestoreService;
use App\Repositories\BaseRepository;
use Modules\Home\Models\LiveStreaming;

class LiveStreamingRepository extends BaseRepository
{

    /**
     * Get current active stream
     *
     * @return LiveStreaming $liveStreaming | bool
     */
    public function getActiveStream()
    {
        $collection = $this->from(new LiveStreaming)
        ->where('onAir', '=', LiveStreaming::ONAIR_TRUE)
        ->first();

        if(! $collection) {
            return false;
        }

        return $collection;
    }

    /**
     * Create a new live
     *
     * @param LiveStreaming $liveStreaming
     * @return void
     */
    public function createLive(LiveStreaming $liveStreaming)
    {
        $collection = $this->from($liveStreaming)
        ->set([
            'title' => $liveStreaming->title,
            'scope' => $liveStreaming->scope,
            'explanation' => '',
            'start' => $this->getTimestamp(),
            'end' => $this->getTimestamp((new \DateTime())->modify('+1 day')->format('Y-m-d H:i:s')),
            'useDrm' => false,
            'useChat' => true,
            'onAir' => true,
            'createdAt' => $this->getTimestamp(),
            'updatedAt' => $this->getTimestamp()
        ]);

        if(! $collection) {
            return false;
        }


        return $collection;
    }

    /**
     * Stop a current livestream
     *
     * @param LiveStreaming $liveStreaming
     * @return void
     */
    public function stopLive(LiveStreaming $liveStreaming)
    {
        $collection = $this->from($liveStreaming)
        ->set([
            'title' => $liveStreaming->title,
            'scope' => $liveStreaming->scope,
            'explanation' => $liveStreaming->explanation,
            'start' => $this->getTimestamp((new \DateTime())->modify('-5 days')->format('Y-m-d H:i:s')),
            'end' => $this->getTimestamp((new \DateTime())->modify('-5 days')->format('Y-m-d H:i:s')),
            'useDrm' => $liveStreaming->useDrm,
            'useChat' => $liveStreaming->useChat,
            'onAir' => $liveStreaming->onAir,
            'updatedAt' => $this->getTimestamp()
        ]);

        if(! $collection) {
            return false;
        }


        return $collection;
    }
}
