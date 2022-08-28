<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Helpers\Performers\Datatable\GetItems;
use Kalnoy\Nestedset\NodeTrait;

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
}