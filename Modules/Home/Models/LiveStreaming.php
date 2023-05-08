<?php

namespace Modules\Home\Models;

use App\Models\DataCollection;

class LiveStreaming extends DataCollection
{
    public $id;
    public $collectionName = 'liveStreaming';

    public $title;
    public $contentUrl;
    public $contentUrlDrmIos;
    public $start;
    public $end;
    public $explanation;
    public $onAir;
    public $scope;
    public $useChat;
    public $useDrm;
    public $createdAt;
    public $updatedAt;

    const ONAIR_TRUE = true;
    const ONAIR_FALSE = false;
}
