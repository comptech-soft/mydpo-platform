<?php

namespace MyDpo\Http\Controllers\Helpers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MyDpo\Helpers\TableValidator;

class ValidationController extends Controller
{

    public function columnValueExists(Request $r) {
        return TableValidator::columnValueExists($r->table, $r->column, $r->value); 
    }

    public function columnValueUnique(Request $r) {
        return TableValidator::columnValueUnique($r->table, $r->column, $r->value, $r->id); 
    }

}