<?php

namespace App\Repositories;

use App\Models\Info;
use App\Services\FirestoreService;
use Google\Cloud\Firestore\DocumentReference;

class InfoRepository
{
    /**
     * @var Info $info
     */
    protected $info;

    /**
     * @var FirestoreService $fireStoreService
     */
    protected $fireStoreService;


    public function __construct(FirestoreService $fireStoreService)
    {
        $this->fireStoreService = $fireStoreService;
    }

    /**
     * Get Info By Id
     *
     * @return Info | boolean
     */
    public function find(Info $info)
    {
        //Set info to class
        $this->info = $info;

        //Get data from firestore
        $resultData = $this->fireStoreService->getDocumentById($this->info);
        
        //Return false if no data found
        if(! $resultData) {
            return false;
        }

        //Set raw data to model data
        $this->info = $this->info->setArrayDataToModel($resultData);

        //Return model class with data
        return $this->info;
    }
}
