<?php

namespace MyDpo\Models\Customer\Dashboard;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Models\Authentication\Role;
use MyDpo\Models\Customer\Customer_base as Customer;
use MyDpo\Models\Customer\Accounts\Account;
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
        'plan-conformare' => \MyDpo\Models\Customer\Dashboard\Summaries\PlanConformare::class,
        'reevaluare' => \MyDpo\Models\Customer\Dashboard\Summaries\Reevaluare::class,
        'documents' => \MyDpo\Models\Customer\Dashboard\Summaries\Documents::class,
        'registre' => \MyDpo\Models\Customer\Dashboard\Summaries\Registre::class,
        'centralizatoare' => \MyDpo\Models\Customer\Dashboard\Summaries\Centralizatoare::class,
        'chestionare' => \MyDpo\Models\Customer\Dashboard\Summaries\Chestionare::class,
        'cursuri' => \MyDpo\Models\Customer\Dashboard\Summaries\Cursuri::class,
        'studii-caz' => \MyDpo\Models\Customer\Dashboard\Summaries\Studiicaz::class,
        'infografice' => \MyDpo\Models\Customer\Dashboard\Summaries\Infografice::class,
        'sfaturi' => \MyDpo\Models\Customer\Dashboard\Summaries\Sfaturidpo::class,
        'dpia' => \MyDpo\Models\Customer\Dashboard\Summaries\Dpia::class,
        'tools' => \MyDpo\Models\Customer\Dashboard\Summaries\Instrumentelucru::class,
    ];

    public static function getByColumns() {

        $r = [];

        foreach(self::all() as $i => $record)
        {
            if( ! array_key_exists($record->column_no, $r) )
            {
                $r[$record->column_no] = [];
            }

            $count = -1;
            if(!! request()->customer_id )
            {
                $count = array_key_exists($record->slug, self::$myclasses) 
                    ? self::$myclasses[$record->slug]::CountLivrabile(request()->customer_id) 
                    : -1;
            }

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

    /**
     * Vizibilitatea pe roluri a itemurilor
     */
    public static function doRolessettingsave($input) {
        return Role::SaveDashboardSettings($input['role_id'], $input['dashboard']);
    }

    /**
     * Vizibilitatea pe clienti a itemurilor
     */
    public static function doCustomerssettingsave($input) {
        return Customer::SaveDashboardSettings($input['customer_id'], $input['dashboard']);
    }

    /**
     * Vizibilitatea pe accounts a itemurilor
     */
    public static function doAccountssettingsave($input) {
        return Account::SaveDashboardSettings($input['user_id'], $input['customer_id'], $input['dashboard']);
    }
    

}