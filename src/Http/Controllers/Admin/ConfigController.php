<?php

namespace MyDpo\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Models\SysConfig;

class ConfigController extends Controller
{
    
    public function index(Request $r) {
        return Response::View(
            '~templates.index', 
            asset('apps/config/index.js')
        );
    }

    public function getItems(Request $r) {
        return SysConfig::getItems($r->all());
    }

    public function doAction($action, Request $r) {
        return SysConfig::doAction($action, $r->all());
    }
}