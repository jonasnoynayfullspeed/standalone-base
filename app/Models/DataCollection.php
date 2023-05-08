<?php

namespace App\Models;

use App\Models\DataCollectionInterface;

class DataCollection implements DataCollectionInterface
{
    protected $parent;

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
     * Set array data to model
     *
     * @return self
     */
    public function setToModel(array $rawData)
    {
        foreach($rawData as $columnName => $value)
        {
            if(property_exists($this, $columnName))
            {
                $this->{$columnName} = $value;
            }
        }

        return $this;
    }

    /**
     * Set parent of collection
     *
     * @param DataCollectionInterface $parent
     */
    public function setParent(DataCollectionInterface $parent)
    {
        $this->parent = $parent;
    }

    /**
     * Get parent from collection
     *
     * @return DataCollectionInterface
     */
    public function getParent()
    {
        return $this->parent ?? null;
    }
}
