<?php

namespace App\Repositories;

use App\Models\DataCollection;
use Google\Cloud\Core\Timestamp;
use App\Services\FirestoreService;
use Google\Cloud\Firestore\DocumentReference;
use Google\Cloud\Firestore\CollectionReference;

class BaseRepository implements RepositoryInterface
{
    /**
     * @var FirestoreService
     */
    protected $db;
    protected $query;
    protected $collection;

    public function __construct(FirestoreService $fireStoreService)
    {
        $this->db = $fireStoreService->fireStore;
    }

    /**
     * Get from collection
     *
     * @param DataCollection $model
     * 
     * @return self
     */
    public function from(DataCollection $collection)
    {
        $this->collection = $collection;
        $db = $this->db;
        $parent = $collection->getParent();
        
        while($parent != null) {
            $db = $db
            ->collection($parent->getCollectionName())
            ->document($parent->getId());

            $parent = $parent->getParent();
        }

        $this->query = $db->collection($collection->getCollectionName());

        if($collection->getId()) {
            $this->query = $this->query->document($collection->getId());
        }

        return $this;
    }

    /**
     * Get collection
     *
     * @return DataCollection|bool
     */
    public function find(DataCollection $collection)
    {
        // Get data from firestore
        $resultData = $this->db->getDocumentById($collection);

        // Return false if no data found
        if (! $resultData) {
            return false;
        }

        // Set raw data to model data
        return $collection->setToModel($resultData);
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

       foreach($documents->rows() as $document) {
            if($document->exists()) {
                $this->query = $document->reference();
                $fireStoreService = new FirestoreService();
                $resultData = $fireStoreService->snapshotToArray($document);
            }
       }

       if($resultData == false) {
            return false;
       }

       return $this->collection->setToModel($resultData);
    }

    /**
     * Set new data array to referrence
     *
     * @param array $newData
     * @return bool
     */
    public function set(array $newData)
    {
        if($this->query instanceof DocumentReference) {
            $this->query->set($newData, ['merge' => true]);
            return true;
            
        }else if($this->query instanceof CollectionReference){
            $this->query->newDocument()->set($newData);
            return true;
        }

        return false;
    }

    /**
     * Get timestamp for firestore
     *
     * @param string $dateString
     * @return Timestamp
     */
    public function getTimestamp(string $dateString = null)
    {
        if ($dateString) {
            return new Timestamp(new \DateTime($dateString));
        }

        return new Timestamp(new \DateTime());
    }
}
