<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;

class EmailVerificationPromptController extends Controller {
    
    public function index(Request $request) {
        if($request->user()->hasVerifiedEmail())
        {
            return redirect()->intended('dashboard');
        }

        return Response::View(
            '~templates.index', 
            asset('apps/email-verify-prompt/index.js'), 
            [], 
            []
        );
    }

}