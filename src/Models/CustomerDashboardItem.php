<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Helpers\Performers\Datatable\GetItems;
use Kalnoy\Nestedset\NodeTrait;
use MyDpo\Performers\CustomerDashboardItem\SaveReorderedItems;
use MyDpo\Performers\CustomerDashboardItem\SaveProfileReorderedItems;

class CustomerDashboardItem extends Model {

    use NodeTrait;

    protected $table = 'customers-dashboard-items';

    protected $casts = [
        'props' => 'json',
    ];
    
    protected $fillable = [
        'id',
        'name',
        'slug',
        'icon',
        'image',
        'title',
        'slot',
        'order_no',
        'visible_on_admin',
        'visible_on_b2b',
        'props',
    ];

    protected $with = ['ancestors'];

    
    public static function getItems($input) {
        return (new GetItems($input, self::query()->with(['children']), __CLASS__))->Perform();
    }

    public static function saveReorderedItems($input) {
        return 
            (new SaveReorderedItems($input))
            ->SetSuccessMessage(NULL)
            ->SetExceptionMessage([
                \Exception::class => NULL,
            ])
            ->Perform();
    }

    public static function saveProfileReorderedItems($input) {
        return 
            (new SaveProfileReorderedItems($input))
            ->SetSuccessMessage(NULL)
            ->SetExceptionMessage([
                \Exception::class => NULL,
            ])
            ->Perform();
    }



    
}