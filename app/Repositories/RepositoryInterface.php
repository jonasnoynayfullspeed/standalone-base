<?php

namespace App\Repositories;

use App\Models\BaseModel;

interface RepositoryInterface
{
    public function create (BaseModel $model);
    public function find   (BaseModel $model);
    public function update (BaseModel $model);
    public function delete (BaseModel $model);
}
