<?php

namespace MyDpo\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use MyDpo\Http\Requests\Auth\LoginRequest;
use MyDpo\Helpers\Response;

class AuthenticatedSessionController extends Controller {

    public function redirectLogin() {
        return redirect('connect');
    }

    /**
     * Display the login view.
     */
    public function create()
    {
        return Response::View('~templates.index', asset('apps/auth/index.js'));
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request) {
        
        
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
