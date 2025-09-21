<?php

namespace Shandialamp\Foodin\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Response;

class AdminResource extends JsonResource
{
    public static function success($data = null, $msg = null, $code = 0)
    {
        return Response::json([
            'code'      => $code,
            'message'   => $msg,
            'error'     => null,
            'data'      => self::convertKeysToCamel($data),
        ]);
    }

    public static function error($msg = '', $code = 0, $error = null, $data = null, $statusCode = 200)
    {
        return Response::json([
            'code'      => $code,
            'message'   => $msg,
            'error'     => $error ?? $msg,
            'data'      => self::convertKeysToCamel($data),
        ], $statusCode);
    }

    // protected static function snakeToCamel($data)
    // {
    //     $result = [];
    //     if (is_iterable($data)) {
    //         foreach ($data as $key => $value) {
    //             var_dump($key);
    //             $key = lcfirst(str_replace('_', '', ucwords($key, '_')));
    //             if (is_iterable($value)) {
    //                 $result[$key] = self::snakeToCamel($value);
    //             } else {
    //                 $result[$key] = $value;
    //             }
    //         }
    //     } else {
    //         return $data;
    //     }
        
    //     return $result;
    // }

    private static function convertKeysToCamel($data)
    {
        if (is_array($data)) {
            $newData = [];
            foreach ($data as $key => $value) {
                $newKey = self::snakeToCamel($key);
                $newData[$newKey] = self::convertKeysToCamel($value);
            }
            return $newData;
        }
        return $data;
    }

    private static function snakeToCamel(string $string): string
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $string))));
    }
}
