<?php

namespace MyDpo\Actions;

class Success {

    public static function Response(
        string $message, 
        array $payload = NULL
    ) {
        return [
            'success' => true,
            'type' => 'success',
            'message' => $message,
            'exception' => NULL,
            'validator' => NULL,
            'payload' => $payload,
        ];
    }

}