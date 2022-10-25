<?php

namespace App\Services;

use App\Models\ModelInterface;
use Google\Cloud\Firestore\FirestoreClient;
use Google\Cloud\Firestore\DocumentSnapshot;

class FirestoreService
{
    /**
     * @var FirestoreClient $fireStore
     */
    public $fireStore;


    public function __construct()
    {
        //Get credentials from storage file
        $credentials = base_path(env('FIREBASE_CREDENTIALS'));

        //New instance of firestore client
        $this->fireStore = new FirestoreClient([
            'keyFile' => json_decode(file_get_contents($credentials), true)
        ]);
    }

    /**
     * Find document by id
     *
     * @param String $documentId
     * @return Array | boolean
     */
    public function getDocumentById(ModelInterface $model)
    {
        //Get first level collection
        $collectionData = $this->fireStore->collection($model->getCollectionName());
        
        //Get document from collection via ID
        $snapshot = $collectionData->document($model->getId())->snapshot();
        
        //Check if snapshot exists
        if(! $snapshot->exists()) {
            return false;
        }

        //Return snapshot to array data
        return $this->snapshotToArray($snapshot);
    }

    /**
     * Generate array data from snapshot
     *
     * @param DocumentSnapshot $snapshot
     * @return Array
     */
    public function snapshotToArray(DocumentSnapshot $snapshot)
    {
        //Get snapshot data id
        $resultId = $snapshot->id();

        //Get snapshot data body
        $resultData = $snapshot->data();

        //Add id from data body
        $resultData['id'] = $resultId;

        return $resultData;
    }
}
