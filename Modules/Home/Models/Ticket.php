<?php

namespace Modules\Home\Models;

use App\Models\DataCollection;

class Ticket extends DataCollection
{
    public $id;
    public $collectionName = 'ticket';
    public $code;
    public $status;
    public $uid;
    public $createdAt;
    public $updatedAt;

    const STATUS_OPEN = 'open';
    const STATUS_CLOSE = 'close';
}
