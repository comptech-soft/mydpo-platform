<?php

namespace MyDpo\Http\Controllers\Customer\Dashboard\Entities;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Customer\Customer;
use MyDpo\Models\Customer\Accounts\Account;

class AccountsController extends Controller {
    
    public function index($customer_id, Request $r) {

        Account::SyncRecords($customer_id);

        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/customer/conturi/index.js'],
            payload: [
                'customer_id' => $customer_id,
                'customer' => Customer::find($customer_id),
                'customer_user' => \Auth::user(),
            ],
        );        
    }

    public function getRecords(Request $r) {
        return Account::getRecords($r->all());
    }
    
    public function doAction($action, Request $r) {
        return Account::doAction($action, $r->all());
    }

    // public function updateRole($action, Request $r) {
    //     return CustomerAccount::updateRole($action, $r->all());
    // }

    // public function updateStatus($action, Request $r) {
    //     return CustomerAccount::updateStatus($action, $r->all());
    // }

    // public function saveDashboardPermissions(Request $r) {
    //     return CustomerAccount::saveDashboardPermissions($r->all());
    // }
    
    // public function saveFolderPermissions(Request $r) {
    //     return CustomerAccount::saveFolderPermissions($r->all());
    // }

    // public function saveFolderAccess(Request $r) {
    //     return CustomerAccount::saveFolderAccess($r->all());
    // }

    // public function savePermissions(Request $r) {
    //     return CustomerAccount::savePermissions($r->all());
    // }

    // public function assignUser(Request $r) {
    //     return CustomerAccount::assignUser($r->all());
    // }

}