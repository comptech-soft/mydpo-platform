<?php

namespace MyDpo\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Models\Category;

class CategoriesController extends Controller {

    public function getItems($type = NULL, Request $r) {
        return Category::getItems($r->all(), $type);
    }

    public function doAction($action, Request $r) {
        return Category::doAction($action, $r->all());
    }

    public function isValidName(Request $r) {
        return Category::isValidName($r->all());
    }

}