<?php

namespace MyDpo\Http\Controllers\Usersession;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use MyDpo\Helpers\UserSession;

class VerifyEmailController extends Controller
{
    
    public function index(EmailVerificationRequest $request) {
        if ($request->user()->hasVerifiedEmail()) 
        {
            return redirect()->intended(RouteServiceProvider::HOME . '?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) 
        {
            $request->user()->activated_at = \Carbon\Carbon::now();
            $request->user()->save();

            event(new Verified($request->user()));
        }

        return redirect()->intended(RouteServiceProvider::HOME . '?verified=1');
    }

    public function sendActivationEmail(Request $r) {
        return UserSession::sendActivationEmail();
    }

    

}