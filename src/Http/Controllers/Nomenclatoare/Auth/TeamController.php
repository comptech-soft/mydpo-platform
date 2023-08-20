<?php

namespace MyDpo\Http\Controllers\Nomenclatoare\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;

class TeamController extends Controller {
    
    public function index(Request $r) {
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/nomenclatoare/auth/team/index.js'],
        );        
    }

}