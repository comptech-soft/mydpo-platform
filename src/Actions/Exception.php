<?php

namespace ComptechSoft\MyHelpers\Classes\Core;

class Exception {

    public static function Response(
        \Exception $e, 
        string $message
    ) {
        return [
            'success' => false,
            'type' => 'error',
            'message' => $message,
            'exception' => [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'class' => get_class($e),
                'time' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
            ],
            'validator' => NULL,
            'payload' => NULL,
        ];
    }

}