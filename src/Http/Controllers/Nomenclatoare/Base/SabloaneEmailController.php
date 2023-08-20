<?php

namespace MyDpo\Http\Controllers\Nomenclatoare\Base;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Livrabile\Emails\TemplateEmail;

class SabloaneEmailController extends Controller {
    
    public function index(Request $r) {
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/nomenclatoare/base/sabloane-email/index.js'],
        );        
    }

    public function getRecords(Request $r) {
        return TemplateEmail::getRecords($r->all());
    }

    public function doAction($action, Request $r) {
        return TemplateEmail::doAction($action, $r->all());
    }
}