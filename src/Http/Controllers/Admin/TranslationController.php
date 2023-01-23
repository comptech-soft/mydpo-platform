<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Models\Translation;

class TranslationController extends Controller {
    
    public function index($customer_id,Request $r) {

        return Response::View(
            '~templates.index', 
            asset('apps/translations/index.js'),
            [], 
            []
        );
    }

    public function getItems(Request $r) {
        return Translation::getItems($r->all());
    }
    
    public function doAction($action, Request $r) {
        return Translation::doAction($action, $r->all());
    }

}