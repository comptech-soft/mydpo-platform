<?php

namespace MyDpo\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Models\Translation;

class TranslationsController extends Controller
{

    public function createKey(Request $r) {
        return Translation::createKey();
    }

}