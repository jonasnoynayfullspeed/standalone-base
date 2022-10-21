<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Info extends Model
{
    public $id = '';
    public $collectionName = 'info';
    public $title, $priority, $date, $link, $type, $createdAt, $updatedAt, $detail, $scope, $isDraft;

    public function __construct($id = '')
    {
        $this->id = $id;
    }


    /**
     * Set Array $data to model
     *
     * @param Array $data
     * @return self
     */
    public function setArrayDataToModel( Array $data)
    {
        $this->title = $data['title'];
        $this->priority = $data['priority'];
        $this->date = $data['date'];
        $this->link = $data['link'];
        $this->type = $data['type'];
        $this->createdAt = $data['createdAt'];
        $this->updatedAt = $data['updatedAt'];
        $this->detail = $data['detail'];
        $this->scope = $data['scope'];
        $this->isDraft = $data['isDraft'];

        return $this;
    }
}
