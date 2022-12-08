<?php

namespace MyDpo\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Models\Sharematerial;

class ShareController extends Controller {
    
    public function index($entity, Request $r) {

        if( in_array($entity, ['curs', 'centralizator', 'chestionar']) )
        {
            return Response::View(
                '~templates.index', 
                asset('apps/share' . $entity . '/index.js'), 
                [], 
                [
                    'entity' => $entity
                ]
            );
        }
        return redirect('/');
    }

    public function getItems(Request $r) {
        return Sharematerial::getItems($r->all());
    }

    public function doAction($action, Request $r) {
        return Sharematerial::doAction($action, $r->all());
    }

    public function getNextNumber(Request $r) {
        return Sharematerial::getNextNumber($r->all());
    }

    

}