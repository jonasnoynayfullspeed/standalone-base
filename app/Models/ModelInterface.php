<?php

namespace App\Models;

interface ModelInterface {

    public function getId();
    public function getCollectionName();
    public function setArrayDataToModel(Array $data);
}
