<?php

namespace MyDpo\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\System\Config;
use MyDpo\Models\Authentication\UserSetting;

class ConfigController extends Controller {

    public function getConfig(Request $r) {
        return Config::getConfig();
    }

    // public function getUserRole(Request $r) {
    //     return Config::getUserRole($r->all());
    // }
    
    // public function saveActiveCustomer(Request $r) {
    //     return UserSetting::saveActiveCustomer($r->all());
    // }
    
    public function setLocale($locale) {
        
        if(! in_array($locale, ['en', 'ro']))
        {
            $locale = 'ro';
        }

        \App::setlocale($locale);
        session()->put('locale', $locale);

        return redirect()->back();
    } 

}