<?php

namespace MyDpo\Http\Controllers\Nomenclatoare\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Authentication\User;

class TeamDashboardController extends Controller {
    
    public function index($user_id, Request $r) {

        $user = User::find($user_id);

        if(! $user )
        {
            return redirect('/');
        }

        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/nomenclatoare/auth/team-dashboard/index.js'],
            payload: [
                'team_member' => $user,
            ],
        );        
    }

}