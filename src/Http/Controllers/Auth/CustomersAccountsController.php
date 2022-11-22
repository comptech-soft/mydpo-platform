<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\Response;
use MyDpo\Models\CustomerAccount;

class CustomersAccountsController extends Controller {
    
    public function index($customer_id,Request $r) {

        CustomerAccount::SyncRecords($customer_id);

        return Response::View(
            '~templates.index', 
            asset('apps/customer-accounts/index.js'),
            [], 
            [
                'customer_id' => $customer_id,
            ]
        );
    }


    public function getItems(Request $r) {
        return CustomerAccount::getItems($r->all());
    }
    
    public function doAction($action, Request $r) {
        return CustomerAccount::doAction($action, $r->all());
    }

    public function updateRole($action, Request $r) {
        return CustomerAccount::updateRole($action, $r->all());
    }

    public function saveDashboardPermissions($action, Request $r) {
        return CustomerAccount::saveDashboardPermissions($r->all());
    }
    
    

}