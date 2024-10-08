<?php

namespace MyDpo\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Models\System\Upload;

class UploadsController extends Controller {

    public function getFileProperties(Request $r) {

        return Upload::getFileProperties($r->only(['file', 'is_image', 'path', 'user_id', 'rules']));

    }

}