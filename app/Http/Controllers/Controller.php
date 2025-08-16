<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function sendResponse($success = true, $message = '', $data = [], $code = 200)
    {
        
        $response = [
            'success' => $success,
            'code'    => $code, 
            'message' => $message,
            'data'    => $data,
        ];

        return response()->json($response, 200);
    }
}
