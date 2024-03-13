<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Customer\Customer;
use MyDpo\Models\Authentication\UserSetting;

class MyProfileController extends Controller {
    
    /**
     * Se ajunge pe pagina '/my-pofile'
     */
    public function index($customer_id = NULL) {

        $user = \Auth::user();

        if(config('app.platform') == 'admin')
        {
            return Index::View(
                styles: ['css/app.css'],
                scripts: ['apps/user/my-profile/index.js'],
                payload: [
                    'customer_user' => $user,
                ],
            );  
        }

        if(! $customer_id )
        {
            $customer_id = UserSetting::GetDefaultCustomer(config('app.platform'), $user);
        }

        if(!! $customer_id )
        {
            return Index::View(
                styles: ['css/app.css'],
                scripts: ['apps/user/my-profile/index.js'],
                payload: [
                    'customer_id' => $customer_id,
                    'customer' => Customer::find($customer_id),
                    'customer_user' => $user,
                ],
            ); 
        }

        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/user/my-profile/index.js'],
            payload: [
                'customer_user' => $user,
            ],
        ); 
    }
}