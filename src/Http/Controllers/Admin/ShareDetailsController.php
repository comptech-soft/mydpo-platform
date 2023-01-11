<?php

namespace MyDpo\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Models\SharematerialDetail;

class ShareDetailsController extends Controller {
    

    public function getItems(Request $r) {
        return SharematerialDetail::getItems($r->all());
    }

}