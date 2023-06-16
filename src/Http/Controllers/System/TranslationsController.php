<?php

namespace MyDpo\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\System\Translation;

class TranslationsController extends Controller
{

    public function index(Request $r) {
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/admin/translations/index.js']
        );        
    }

    public function getRecords(Request $r) {
        return Translation::getRecords($r->all());
    }

    public function doAction($action, Request $r) {
        return Translation::doAction($action, $r->all());
    }

    public function activate(Request $r) {
        return Translation::activate($r->all());
    }

    public function createKey(Request $r) {
        return Translation::createKey($r->all());
    }

}