<?php

namespace MyDpo\Http\Controllers\Nomenclatoare\Base;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Livrabile\Categories\Category;

class CategoriesController extends Controller {

    public function index(Request $r) {
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/nomenclatoare/base/categories/index.js'],
        );        
    }

    public function getRecords(Request $r) {
        return Category::getRecords($r->all());
    }

    public function doAction($action, Request $r) {
        return Category::doAction($action, $r->all());
    }

    // public function isValidName(Request $r) {
    //     return Category::isValidName($r->all());
    // }

}