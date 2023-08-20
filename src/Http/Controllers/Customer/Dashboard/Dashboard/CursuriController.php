<?php

namespace MyDpo\Http\Controllers\Customer\Dashboard\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Core\Http\Response\Index;
use MyDpo\Models\Customer\Customer;

class CursuriController extends Controller {

    public function index($customer_id, Request $r) {
        return Index::View(
            styles: ['css/app.css'],
            scripts: ['apps/customer/elearning/index.js'],
            payload: [
                'customer_id' => $customer_id,
                'customer' => Customer::find($customer_id),
            ],
        );        
    }

    
    // public function index($customer_id, Request $r) {

    //     if( config('app.platform') == 'b2b')
    //     {
    //         $activation = Activation::byUserAndCustomer($user_id = \Auth::user()->id, $customer_id);

    //         if( ! $activation || ($activation->activated == 0))
    //         {
    //             return redirect('/');
    //         }

    //         $role = \Auth::user()->roles()->wherePivot('customer_id', $customer_id)->get()->first();

    //         if($activation->role_id != $role->id)
    //         {
    //             return redirect('/');
    //         }

    //         if($role->slug == 'customer')
    //         {
    //             return redirect('/cursurile-mele/' . $customer_id);
    //         }
    //     }

    //     CustomerCurs::syncUsersCounts($customer_id);

    //     return Response::View(
    //         '~templates.index', 
    //         asset('apps/customer-cursuri/index.js'),
    //         [], 
    //         [
    //             'customer_id' => $customer_id,
    //         ]
    //     );
    // }

    // public function indexMyCursuri($customer_id, Request $r) {
    //     return Response::View(
    //         '~templates.index', 
    //         asset('apps/cursurile-mele/index.js'),
    //         [], 
    //         [
    //             'customer_id' => $customer_id,
    //         ]
    //     );
    // }

    // public function downloadFile($customer_id, $file_id, Request $r) {
    //     return CustomerCursFile::downloadFile($customer_id, $file_id);
    // }

    // public function getItems(Request $r) {
    //     return CustomerCurs::getItems($r->all());
    // }

    // public function getSummary(Request $r) {
    //     return CustomerCurs::getSummary($r->all());
    // }

    // public function stergereParticipant(Request $r) {
    //     return CustomerCurs::stergereParticipant($r->all());
    // }

    // public function stergereFisier(Request $r) {
    //     return CustomerCurs::stergereFisier($r->all());
    // }

    // public function desasociereUtilizatori(Request $r) {
    //     return CustomerCurs::desasociereUtilizatori($r->all());
    // }

    // public function desasociereUsers(Request $r) {
    //     return CustomerCurs::desasociereUsers($r->all());
    // }
    
}