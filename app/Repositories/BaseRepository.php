<?php

namespace App\Repositories;

use App\Models\ModelInterface;
use App\Services\FirestoreService;

class BaseRepository implements RepositoryInterface
{
    /**
     * @var FirestoreService
     */
    protected $db;
    protected $model;

    public function __construct(FirestoreService $fireStoreService)
    {
        $this->db = $fireStoreService->fireStore;
    }


    /**
     * Get from collection
     *
     * @param ModelInterface $model
     * 
     * @return self
     */
    public function from(ModelInterface $model)
    {
        $this->model = $model;

        $this->query = $this->db->collection($model->getCollectionName());

        return $this;
    }

    /**
     * Get model.
     *
     * @return ModelInterface|bool
     */
    public function find(ModelInterface $model)
    {
        // Get data from firestore
        $resultData = $this->db->getDocumentById($model);

        // Return false if no data found
        if (! $resultData) {
            return false;
        }

        // Set raw data to model data
        return $model->setArrayDataToModel($resultData);
    }

    /**
     * Collection where filter
     *
     * @param string $whereField
     * @param string $whereOperator
     * @param string $whereValue
     * 
     * @return self
     */
    public function where($whereField = null, $whereOperator = null, $whereValue = null)
    {
       $this->query = $this->query->where($whereField, $whereOperator, $whereValue);

       return $this;
    }

    /**
     * Get first data from query documents
     * 
     * @return array|bool
     */
    public function first()
    {
        $resultData = false;

        $documents = $this->query->documents();

       foreach($documents->rows() as $document)
       {
            if($document->exists())
            {
                $resultData = $this->snapshotToArray($document);
            }
       }

       if($resultData == false)
       {
            return false;
       }

       $this->model->setArrayDataToModel($resultData);
       
       return $this->model;
    }
}
