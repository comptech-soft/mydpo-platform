<?php

namespace MyDpo\Models\System;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use MyDpo\Performers\SysMenu\Settingrolesvisibility;
use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;
use MyDpo\Traits\Reorderable;

class SysMenu extends Model {

    use NodeTrait, Itemable, Actionable, Reorderable;
    
    protected $table = 'system-menus';

    protected $fillable = [
        'id',
        'slug',
        'type',
        'platform',
        'order_no',
        'caption',
        'icon',
        'link',
        'description',
        'props',
        'parent_id',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'id' => 'integer',
        'platform' => 'json',
        'props' => 'json',
        'order_no' => 'integer',
        'parent_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    protected $with = [
        'children',
    ];

    // public static function GetBySlug($slug) {
    //     return self::whereSlug($slug)->first();
    // }

    public static function doInsert($input, $record) {

        if(! $input['parent_id'] )
        {
            $record = self::create($input);
        }
        else
        {
            $parent = self::find( $input['parent_id'] );
            $record = $parent->children()->create($input);
        }

        return self::find($record->id);
    }

    public static function doSettingrolesvisibility($input, $record) {
        $result =  (new Settingrolesvisibility($input))->Perform();

        if( ! $result['payload']['success'])
        {
            return ! $result['payload'];
        }

        dd($result);
    }

    // public static function getVisibilities($input) {
    //     return (new GetVisibilities($input))->Perform();
    // }
    
    /**
     * 14.06.2023
     * Returneaza meniurile definite in sistem
     * Se tine cont de platforma
     */
    public static function getMenus() 
    {
        $r = [];
        foreach(self::whereIsRoot()->get() as $i => $item)
        {
            if(in_array(config('app.platform'), $item->platform))
            {
                $r[$item->slug] = $item;
            }
        }
        return $r;
    }

}