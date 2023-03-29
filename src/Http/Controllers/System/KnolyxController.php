<?php

namespace MyDpo\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use MyDpo\Helpers\Response;

class KnolyxController extends Controller {

    public function webhookProcess(Request $r) {


        $q = [
            'id' => '8afa72f7-b20f-463a-9257-68a92d43db1e',
            'type' => 'COURSE_COMPLETED',
            'data' => [
                'user' => [
                    'id' => 38229,
                    'name' => '[unknown] [unknown]',
                    'email' => 'ovidiua.andrus@comptech.ro',
                    'organizationId' => 173,
                ],
                'course' => [
                    'id' => 2455,
                    'name' => 'Intro to marketing',
                ],
            ],
        ];


        return $q;

        // $params = [
        //     "id" => "af46ec07-de79-40b5-916c-0058b54b2d2b", // unique id, messages with the same id should be ignored
        //     "type" => "PING",
        //     "data" => [
        //         "message" => "PING"
        //     ]
        // ];

        // return $params;
    }

}