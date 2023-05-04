<?php

namespace App\Models;

class LiveStreamingBase extends BaseModel
{
    protected $id;
    protected $collectionName = 'liveStreaming';

    public $title;
    public $start;
    public $end;
    public $explanation;
    public $contentUrl;
    public $contentUrlDrmIos;
    public $createdAt;
    public $updatedAt;
    public $onAir;
    public $scope;
    public $useChat;
    public $useDrm;

    /**
     * Set Array $data to model.
     *
     * @return self
     */
    public function setArrayDataToModel(array $data)
    {
        $this->title            = $data['title'];
        $this->start            = $data['start'];
        $this->end              = $data['end'];
        $this->explanation      = $data['explanation'];
        $this->contentUrl       = $data['contentUrl'];
        $this->contentUrlDrmIos = $data['contentUrlDrmIos'];
        $this->createdAt        = $data['createdAt'];
        $this->updatedAt        = $data['updatedAt'];
        $this->onAir            = $data['onAir'];
        $this->scope            = $data['scope'];
        $this->useChat          = $data['useChat'];
        $this->useDrm           = $data['useDrm'];

        return $this;
    }
}
