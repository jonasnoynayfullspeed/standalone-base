<?php

namespace App\Http\Controllers;

use App\Repositories\AuthRepository;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private $authRepository;
    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        extract($credentials);

        if ($this->authRepository->authenticate($username, $password)) {
            return redirect('/');
        }
        return redirect('signIn')->withErrors([
            'error' => 'Failed to login!'
        ]);
    }

    public function logout()
    {
        if ($this->authRepository->logout()) {
            return redirect('/signIn');
        }
    }

    public function signIn()
    {
        return view('signin');
    }
}
