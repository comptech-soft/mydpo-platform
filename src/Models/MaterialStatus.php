<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Helpers\Performers\Datatable\GetItems;

class MaterialStatus extends Model {

    protected $table = 'materiale-statuses';

    protected $casts = [
        'applied_to' => 'json',
    ];

    protected $fillable = [
        'id',
        'name',
        'slug',
        'applied_to',
    ];

    protected $appends = [
        'at_documente',
    ];

    public function getAtDocumenteAttribute() {
        if( array_key_exists('documente', $this->applied_to) )
        {
            return $this->applied_to['documente'] == 1;
        }
        return false;
    }

    public function getAtCursuriAttribute() {
        if( array_key_exists('cursuri', $this->applied_to) )
        {
            return $this->applied_to['cursuri'] == 1;
        }
        return false;
    }

    public static function getItems($input) {
        return (new GetItems($input, self::query(), __CLASS__))->Perform();
    }


}