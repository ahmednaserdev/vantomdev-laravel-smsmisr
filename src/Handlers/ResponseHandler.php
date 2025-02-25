<?php

namespace VantomDev\SmsMisr\Handlers;

class ResponseHandler
{
    public static function handle($response)
    {
        $data = json_decode($response, true);

        if ($data['code'] !== '4901' && $data['code'] !== '1901') {
            return [
                'error' => true,
                'message' => 'Error Code: ' . $data['code'],
            ];
        }

        return [
            'error' => false,
            'data' => $data,
        ];
    }
}
