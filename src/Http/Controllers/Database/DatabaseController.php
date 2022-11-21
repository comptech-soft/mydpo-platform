<?php

namespace MyDpo\Http\Controllers\Database;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Performers\Database\UpdateField;

class DatabaseController extends Controller
{

    public function updateField(Request $r) {
        return 
            (
                new UpdateField
                (
                    $r->input,
                    $r->rules,
                )
            )
            ->SetSuccessMessage(NULL)
            ->SetExceptionMessage([
                \Exception::class => NULL,
            ])
            ->Perform();
    }


}