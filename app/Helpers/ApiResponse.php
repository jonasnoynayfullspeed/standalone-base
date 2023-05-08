<?php

namespace App\Helpers;

use Log;
use Illuminate\Http\JsonResponse;

class ApiResponse extends JsonResponse
{
    public static function success(array $data = [])
    {
        $response = array_merge(
            ['status' => self::HTTP_OK],
            $data
        );

        return self::logAndRespond($response);
    }

    public static function badRequest(string $message = '')
    {
        $status = self::HTTP_BAD_REQUEST;
        return self::logAndRespond(compact('message', 'status'));
    }

    public static function notAcceptable($message = 'not acceptable')
    {
        $status = self::HTTP_NOT_ACCEPTABLE;
        return self::logAndRespond(compact('message', 'status'));
    }

    public static function systemError($message = 'system error')
    {
        $status = self::HTTP_INTERNAL_SERVER_ERROR;
        return self::logAndRespond(compact('status', 'message'));
    }

    public static function logAndRespond($response)
    {
        Log::channel('api')->info($response);

        return new JsonResponse($response, $response['status']);
    }
}
