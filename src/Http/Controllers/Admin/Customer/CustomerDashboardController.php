<?php

namespace MyDpo\Http\Controllers\Admin\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Customer;
use MyDpo\Models\CustomerDashboardItem;

class CustomerDashboardController extends Controller {


    public function index($customer_id, Request $r) {
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/customer/dashboard/index.js'],
            payload: [
                'customer_id' => $customer_id,
                'customer' => Customer::find($customer_id),
                'dashboard_items' => CustomerDashboardItem::getByColumns(),
            ],
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