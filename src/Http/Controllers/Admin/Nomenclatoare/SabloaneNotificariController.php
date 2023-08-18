<?php

namespace MyDpo\Http\Controllers\Admin\Nomenclatoare;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Livrabile\Notifications\TemplateNotification;

class SabloaneEmailController extends Controller {
    
    public function index(Request $r) {
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/admin/sabloane-notificari/index.js']
        );        
    }

    public function getRecords(Request $r) {
        return TemplateNotification::getRecords($r->all());
    }

    public function doAction($action, Request $r) {
        return TemplateNotification::doAction($action, $r->all());
    }
}