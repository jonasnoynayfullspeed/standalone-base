<?php

namespace App\Repositories;

use App\Models\Info;
use App\Services\FirestoreService;
use App\Repositories\RepositoryInterface;
use Google\Cloud\Firestore\DocumentReference;

class InfoRepository implements RepositoryInterface
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
     * Get info
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

    /**
     * Delete info
     *
     * @param Info $info
     * @return void
     */
    public function delete(Info $info)
    {
        //
    }

    /**
     * Update info
     *
     * @param Info $info
     * @return void
     */
    public function update(Info $info)
    {
        //
    }

    /**
     * Create info
     *
     * @param Info $info
     * @return void
     */
    public function create(Info $info)
    {
        //
    }
}
