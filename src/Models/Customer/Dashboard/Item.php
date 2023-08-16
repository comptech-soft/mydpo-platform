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
    
    protected static $myclasses = [
        'analiza-gap' => \MyDpo\Models\Customer\Dashboard\Summaries\AnalizaGap::class,
        // 'plan-conformare' => \MyDpo\Models\Customer\Summaries\Planconformare::class,
        // 'registre' => \MyDpo\Models\Customer\Registre\Registru::class,
        // 'centralizatoare' => \MyDpo\Models\Customer\Centralizatoare\Centralizator::class,
        // 'cursuri' => \MyDpo\Models\Customer\Summaries\ELearning::class,
        // 'accounts' => \MyDpo\Models\Customer\Accounts\Account::class,
        // 'team' => \MyDpo\Models\Customer\Teams\Team::class,
        // 'monthly-reports' => \MyDpo\Models\Customer\Rapoartelunare\RaportLunar::class,
        // 'tasks' => \MyDpo\Models\Customer\Taskuri\Task::class,
        // 'emails' => \MyDpo\Models\Customer\Emails\Email::class,
        // 'notifications' => \MyDpo\Models\Customer\Notifications\Notification::class,
    ];

    public static function getByColumns() {

        $r = [];

        foreach(self::all() as $i => $record)
        {
            if( ! array_key_exists($record->column_no, $r) )
            {
                $r[$record->column_no] = [];
            }

            $count = array_key_exists($record->slug, self::$myclasses) 
                ? self::$myclasses[$record->slug]::CountLivrabile(request()->customer_id) 
                : -1;


            $r[$record->column_no][] = [
                ...$record->toArray(),
                'count' => $count,
            ];
        }

        foreach($r as $key => $items)
        {
            $r[$key] = collect($items)->sortBy('order_no')->toArray();
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