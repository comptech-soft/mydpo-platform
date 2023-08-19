<?php

namespace MyDpo\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
// use MyDpo\Models\Authentication\User;

class TeamController extends Controller {
    
    public function index(Request $r) {
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/authentication/team/index.js'],
        );        
    }

}