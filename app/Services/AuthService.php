<?php

namespace App\Services;

use Log;
use Illuminate\Http\Request;

class AuthService
{
    public const SESSION_AUTH = 'auth';
    public const API_TOKEN    = 'apiToken';

    public function checkAuth(Request $request, $guard)
    {
        switch ($guard) {
            case 'api':
                return $this->checkApi($request->header(AuthService::API_TOKEN));
            case 'admin':
            default:
                return $this->checkSession();
        }
    }

    public function checkSession()
    {
        try {
            $session = json_decode(session()->get(self::SESSION_AUTH), true);

            return $session && $session['time'] > time();
        } catch (\Throwable $error) {
            Log::error(__METHOD__, $error->getMessage());
        }

        return false;
    }

    public function checkApi(?string $apiToken)
    {
        return env('API_TOKEN') == $apiToken;
    }

    public function login($username, $password)
    {
        try {
            if ($username == env('MANAGE_ACCOUNT') && $password == env('MANAGE_PASSWORD')) {
                session()->put(self::SESSION_AUTH, json_encode([
                    'username' => env('MANAGE_ACCOUNT'),
                    'password' => env('MANAGE_PASSWORD'),
                    'time'     => strtotime(env('SESSION_EXPIRATION', '+1 day'), time()),
                ]));

                return true;
            }
        } catch (\Throwable $error) {
            Log::error(__METHOD__, $error->getMessage());
        }

        return false;
    }

    public function logout()
    {
        try {
            return session()->remove(self::SESSION_AUTH);
        } catch (\Throwable $error) {
            Log::error(__METHOD__, $error->getMessage());
        }

        return false;
    }
}
