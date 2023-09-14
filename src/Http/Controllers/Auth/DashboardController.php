<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Nomenclatoare\Livrabile\ELearning\Knolyx;
use MyDpo\Models\Authentication\UserSetting;

class DashboardController extends Controller {
    
    public function index(Request $r) {

        /**
         * Se creeaza webhook-ul Knloyx pentru terminarea cursurilor
         */
        Knolyx::createWebhook();
        
        $user = \Auth::user();

        /**
         * Suntem pe platforma admin
         */
        if(config('app.platform') == 'admin')
        {

            /**
             * Admin. Userul nu are emailul verificat
             */
            if(! $user->email_verified_at )
            {
                // apps/email-verify-prompt/index.js
                return Index::View(
                    styles: ['css/app.css'],
                    scripts: ['apps/hamham.js']
                );    
            }

            /**
             * Admin. Ajungem pe dashboardul principal
             */
            return Index::View(
                styles: ['css/app.css'],
                scripts: ['apps/system/dashboard/index.js']
            );
        }

        /**
         * Suntem pe platforma b2b
         */

        /** 
         * Avem default customer
         */
        if(!! ( $customer_id = UserSetting::GetDefaultCustomer(config('app.platform'), $user)) )
        {
            return redirect(config('app.url') . '/customer-dashboard/' . $customer_id);
        }

        dd(config('app.platform'), $customer_id);
        // if($settings = $user->settings()->where('code', 'b2b-active-customer')->first())
        // {
        //     
        // }
        
        // dd($user->customers);
        
        // if($user->customers->count());
        // {
        //     return redirect(route('b2b.dashboard', [
        //         'customer_id' => $user->customers[0],
        //     ]));
        // }

    
        
    }
}