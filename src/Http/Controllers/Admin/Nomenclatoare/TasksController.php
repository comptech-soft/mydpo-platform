<?php

namespace MyDpo\Http\Controllers\Admin\Nomenclatoare;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Livrabile\Tasks\Task;

class TasksController extends Controller {
    
    public function index(Request $r) {
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/admin/tasks/index.js']
        );        
    }

    public function getRecords(Request $r) {
        return Task::getRecords($r->all());
    }

    public function doAction($action, Request $r) {
        return Task::doAction($action, $r->all());
    }
}