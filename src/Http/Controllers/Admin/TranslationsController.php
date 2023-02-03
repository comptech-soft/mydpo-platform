<?php

namespace MyDpo\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Models\Translation;

class TranslationsController extends Controller {
    
    public function index(Request $r) {

        return Response::View(
            '~templates.index', 
            asset('apps/translations/index.js'),
            [], 
            []
        );
    }

    public function createFile(Request $r) {
        return Translation::createFile($r->all());
    }

    public function activate(Request $r) {
        return Translation::activate($r->all());
    }

    public function getItems(Request $r) {
        return Translation::getItems($r->all());
    }
    
    public function doAction($action, Request $r) {
        return Translation::doAction($action, $r->all());
    }

}