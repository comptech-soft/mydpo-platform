<?php

namespace MyDpo\Models\Customer\Dashboard;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Helpers\Performers\Datatable\GetItems;
use MyDpo\Traits\Actionable;

class Item extends Model {

    use Actionable;

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

    /**
     * Vizibilitatea globala a itemurilor
     */
    public static function doGlobalsettingsave($input) {

        foreach($input['dashboard'] as $i => $item)
        {
            $record = self::find($item['id']);

            if(! $record->props )
            {
                $record->props = [];
            }

            $settings = array_key_exists('settings', $record->props) ? $record->props['settings'] : [];

            $settings = [
                ...$settings,
                'visible' => $item['visible'],
                'disabled' => $item['disabled'],
            ];

            $record->props = [
                ...$record->props,
                'settings' => $settings,
            ];

            $record->save();

        }

        return self::getByColumns();

    }

}