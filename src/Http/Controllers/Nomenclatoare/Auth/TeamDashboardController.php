<?php

namespace MyDpo\Http\Controllers\Nomenclatoare\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;

class TeamDashboardController extends Controller {
    
    public function index($user_id, Request $r) {

        dd($user_id);
        
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/nomenclatoare/auth/team-dashboard/index.js'],
        );        
    }

}