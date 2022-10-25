<?php

namespace App\Models;

use App\Models\ModelInterface;

class BaseModel implements ModelInterface
{

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
     * Undocumented function
     *
     * @param Array $data
     * @return void
     */
    public function setArrayDataToModel(Array $data)
    {
        return $this;
    }
}
