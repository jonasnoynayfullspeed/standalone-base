<?php

namespace App\Services;

use Log;
use Kreait\Firebase\Factory;
use Kreait\Firebase\JWT\Token;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Encoding\JoseEncoder;

class FirebaseService
{
    private $factory;

    public function __construct()
    {
        $credentials = base_path(env('FIREBASE_CREDENTIALS'));
        
        $this->factory = (new Factory)->withServiceAccount($credentials);
    }

    public function verifyIdToken($idToken)
    {
        $auth = $this->factory->createAuth();
        try {
            $newToken = $auth->createCustomToken('mzN3iQIgfIdY2JsJEl1phqJzv8r1')->toString();
            $verifiedIdToken = $auth->verifyIdToken($newToken);
            return $verifiedIdToken->getClaim('sub');

        } catch (Firebase\Auth\Token\Exception\ExpiredToken $e) {
            Log::error('Expire', $e->getMessage());
            return false;
        } catch (Exception $e) {
            Log::error('Error', $e->getMessage());
            return false;
        }
    }

    public function verifyAndCreateCustomToken($idToken)
    {
        $auth = $this->factory->createAuth();
        try {
            $verifiedIdToken = $auth->verifyIdToken($idToken);
            $uid = $verifiedIdToken->getClaim('sub');
            $customToken = self::createCustomToken($uid);
            return compact('uid', 'customToken');

        } catch (Firebase\Auth\Token\Exception\ExpiredToken $e) {
            Log::warn(__METHOD__, $e->getMessage());
            return false;
        } catch (Firebase\Auth\Token\Exception\UnknownKey $e) {
            Log::warn(__METHOD__, $e->getMessage());
            return false;
        } catch (Exception $e) {
            Log::error(__METHOD__, get_class($e), $e->getMessage());
            return false;
        }
    }


    public function createCustomToken($uid)
    {
        $auth = $this->factory->createAuth();
        try {
            return $auth->createCustomToken($uid)->toString();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }

    public function getAuth()
    {
        $auth = $this->factory->createAuth();
        try {
            return $auth->listUsers();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }
}
