<?php

namespace App\Repositories;

use App\Models\InfoBase as Info;

interface RepositoryInterface
{
    public function create(Info $info);
    public function find(Info $info);
    public function update(Info $info);
    public function delete(Info $info);
}
