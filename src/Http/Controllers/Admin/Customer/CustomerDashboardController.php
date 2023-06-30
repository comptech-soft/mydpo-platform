<?php

namespace MyDpo\Http\Controllers\Admin\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Models\UserCustomer;

class CustomerDashboardController extends Controller {


    public function index(Request $r) {
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/admin/customer-dashboard/index.js']
        );        
    }

    
    // public function index($customer_id, Request $r) {

    //     $user = \Auth::user();

    //     if( $user->role && ($user->role->slug == 'operator') )
    //     {
    //         $exists = UserCustomer::where('user_id', $user->id)->where('customer_id', $customer_id)->first();
    //         if( ! $exists )
    //         {
    //             return redirect(config('app.url'));
    //         }
    //     }

        
    //     return Response::View(
    //         '~templates.index', 
    //         asset('apps/customer-dashboard/index.js'),
    //         [], 
    //         [
    //             'customer_id' => $customer_id,
    //         ]
    //     );
        
    // }
}