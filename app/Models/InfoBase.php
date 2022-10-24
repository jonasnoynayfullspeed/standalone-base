<?php

namespace App\Models;

use App\Models\ModelInterface;

class InfoBase implements ModelInterface
{
    protected $id = '';
    protected $collectionName = 'info';

    public  $title, 
            $priority,
            $date,
            $link,
            $type,
            $createdAt,
            $updatedAt, 
            $detail,
            $scope,
            $isDraft;

    public function __construct($id = '')
    {
        $this->id = $id;
    }

    /**
     * Get model id
     *
     * @return String
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get collection name
     *
     * @return String
     */
    public function getCollectionName()
    {
        return $this->collectionName;
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
