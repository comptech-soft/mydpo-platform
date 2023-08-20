<?php

namespace MyDpo\Http\Controllers\Nomenclatoare\Base;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Livrabile\Activitati\Activitate;

class ActivitatiController extends Controller {
    
    public function index(Request $r) {
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/admin/tasks/index.js']
        );        
    }

    public function getRecords(Request $r) {
        return Activitate::getRecords($r->all());
    }

    public function doAction($action, Request $r) {
        return Activitate::doAction($action, $r->all());
    }
}