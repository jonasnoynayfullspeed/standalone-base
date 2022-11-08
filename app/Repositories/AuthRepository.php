<?php

namespace App\Repositories;

use App\Services\AuthService;

class AuthRepository
{
    private $authService;

    public function __construct(AuthService $authService) {
        $this->authService = $authService;
    }
    
    public function authenticate($username, $password)
    {
        return $this->authService->login($username, $password);
    }

    public function logout()
    {
        return $this->authService->logout();
    }
}
