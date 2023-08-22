<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Livrabile\ELearning\Knolyx;

class DashboardController extends Controller {
    
    public function index(Request $r) {

        dd(__METHOD__);
        Knolyx::createWebhook();
        
        /**
         * admin ==> dashboard
         * b2b   ==> my-dashboard
         */

        $user = \Auth::user();

        $asset = (config('app.platform') == 'admin') ? 'dashboard' : 'my-dashboard';

        if(config('app.platform') == 'admin')
        {
            if(! $user->email_verified_at )
            {
                return Response::View(
                    '~templates.index', 
                    asset('apps/email-verify-prompt/index.js'),
                    [],
                    $r->all()
                );        
            }

            return Index::View(
                styles: ['css/app.css'],
                scripts: ['apps/system/dashboard/index.js']
            );
        }

        dd(__METHOD__);

        if($settings = $user->settings()->where('code', 'b2b-active-customer')->first())
        {
            return redirect(route('b2b.dashboard', [
                'customer_id' => $settings->value,
            ]));

        }
        
        if($user->customers->count());
        {
            return redirect(route('b2b.dashboard', [
                'customer_id' => $user->customers[0],
            ]));
        }

    
        
    }
}