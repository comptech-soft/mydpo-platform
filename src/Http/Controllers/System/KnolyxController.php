<?php

namespace MyDpo\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use MyDpo\Helpers\Response;

class KnolyxController extends Controller {

    public function webhookProcess(Request $r) {
        $params = [
            "id" => "af46ec07-de79-40b5-916c-0058b54b2d2b", // unique id, messages with the same id should be ignored
            "type" => "PING",
            "data" => [
                "message" => "PING"
            ]
        ];

        return $params;
    }

}