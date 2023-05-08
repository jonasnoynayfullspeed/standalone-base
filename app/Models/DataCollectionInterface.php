<?php

namespace App\Models;

interface DataCollectionInterface
{
    /**
     * Get collection id
     */
    public function getId();

    /**
     * Get collection name
     */
    public function getCollectionName();

    /**
     * Set array of data to model
     *
     * @param array $data
     */
    public function setToModel(array $data);
}
