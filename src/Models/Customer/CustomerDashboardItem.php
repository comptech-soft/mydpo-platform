<?php

namespace MyDpo\Models\Customer;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Helpers\Performers\Datatable\GetItems;
// use MyDpo\Performers\CustomerDashboardItem\SaveReorderedItems;
// use MyDpo\Performers\CustomerDashboardItem\SaveProfileReorderedItems;

class CustomerDashboardItem extends Model {

    use NodeTrait;

    protected $table = 'customers-entities-items';

    protected $casts = [
        'props' => 'json',
        'platform' => 'json',
    ];
    
    protected $fillable = [
        'id',
        'title',
        'slug',
        'platform',
        'image',
        'order_no',
        'props',
    ];
   
    public static function getByColumns() {

        $r = [];

        foreach(self::all() as $i => $record)
        {
            if( ! array_key_exists($record->column_no, $r) )
            {
                $r[$record->column_no] = [];
            }
            $r[$record->column_no][] = $record;
        }

        foreach($r as $key => $items)
        {
            $r[$key] = collect($items)->sortBy('order_no');
        }

        return $r;
    }

    // public static function saveReorderedItems($input) {
    //     return 
    //         (new SaveReorderedItems($input))
    //         ->SetSuccessMessage(NULL)
    //         ->SetExceptionMessage([
    //             \Exception::class => NULL,
    //         ])
    //         ->Perform();
    // }

    // public static function saveProfileReorderedItems($input) {
    //     return 
    //         (new SaveProfileReorderedItems($input))
    //         ->SetSuccessMessage(NULL)
    //         ->SetExceptionMessage([
    //             \Exception::class => NULL,
    //         ])
    //         ->Perform();
    // }

}