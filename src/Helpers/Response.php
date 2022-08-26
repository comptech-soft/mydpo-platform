<?php

namespace MyDpo\Helpers;

class Response {

    public static function Error($message, $input, $payload = NULL) {
        return [
            'success' => false,
            'type' => 'error',
            'message' => $message,
            'exception' => NULL,
            'validator' => NULL,
            'input' => $input,
            'payload' => $payload,
        ];
    }

    public static function OK($message, $input, $payload = NULL) {
        return [
            'success' => true,
            'type' => 'success',
            'message' => $message,
            'exception' => NULL,
            'validator' => NULL,
            'input' => $input,
            // 'config' => Config::get(),
            'payload' => $payload,
        ];
    }

    public static function Invalid($validator, $input, $message = 'Datele nu sunt valide.') {
        return [
            'success' => false,
            'type' => 'error',
            'message' => $message,
            'exception' => NULL,
            'validator' => [
                'errors' => $validator->errors(),
                'rules' => $validator->getRules(),
                'messages' => $validator->customMessages,
                'data' => $validator->getData(),
            ],
            'input' => $input,
            // 'config' => Config::get(),
            'payload' => NULL,
        ];
    }

    public static function Exception(\Exception $e, $input, $message = 'An error occurred while performing the operation!') {

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
            'input' => $input,
            // 'config' => Config::get(),
            'payload' => NULL,
        ];

    }

    public static function View($template = '~templates.index', $scripts = [], $styles = [], $payload = []) {
        if( is_string($scripts) )
        {
            $scripts = [$scripts];
        }
        if( is_string($styles) )
        {
            $styles = [$styles];
        }
        return view($template)->withScripts($scripts)->withPayload($payload)->withStyles($styles);
    }

}