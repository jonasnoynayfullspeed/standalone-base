<?php

namespace App\Models;

class BaseModel implements ModelInterface
{
    public function __construct($id = '')
    {
        $this->id = $id;
    }

    /**
     * Get model id.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get collection name.
     *
     * @return string
     */
    public function getCollectionName()
    {
        return $this->collectionName;
    }

    /**
     * Undocumented function.
     *
     * @return void
     */
    public function setArrayDataToModel(array $data)
    {
        return $this;
    }
}
