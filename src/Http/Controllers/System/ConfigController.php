<?php

namespace MyDpo\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\System\Config;

class ConfigController extends Controller
{

    public function getConfig(Request $r) {
        return Config::getConfig();
    }

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