<?php

namespace App\Services\Common;


/**
 * Class ResponseService
 * @package App\Services
 */
class ResponseService
{
    /**
     * @param bool $status
     * @param Collection|array $data
     * @param string $message
     *
     * @return Symfony\Component\HttpFoundation\Response $response
     */

    public function response(bool $status, $data, string $message)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data ?? []
        ]);
    }
}
