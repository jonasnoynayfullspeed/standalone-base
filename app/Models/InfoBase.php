<?php

namespace App\Models;

class InfoBase extends BaseModel
{
    protected $id;
    protected $collectionName = 'info';

    public $title;
    public $priority;
    public $date;
    public $link;
    public $type;
    public $createdAt;
    public $updatedAt;
    public $detail;
    public $scope;
    public $isDraft;

    /**
     * Set Array $data to model.
     *
     * @return self
     */
    public function setArrayDataToModel(array $data)
    {
        $this->title     = $data['title'];
        $this->priority  = $data['priority'];
        $this->date      = $data['date'];
        $this->link      = $data['link'];
        $this->type      = $data['type'];
        $this->createdAt = $data['createdAt'];
        $this->updatedAt = $data['updatedAt'];
        $this->detail    = $data['detail'];
        $this->scope     = $data['scope'];
        $this->isDraft   = $data['isDraft'];

        return $this;
    }
}
