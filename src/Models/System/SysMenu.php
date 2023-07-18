<?php

namespace MyDpo\Models\System;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
// use MyDpo\Performers\SysMenu\Settingrolesvisibility;
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
        'roles',
    ];

    function roles() {
        return $this->hasMany(SysMenuRole::class, 'menu_id');
    }

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

       
        $menu = self::find($input['menu_id']);

        $result = $menu->Settingrolesvisibility($input);

        foreach($menu->children as $i => $child)
        {
            $input = [
                ...$input,
                'menu_id' => $child->id,
            ];

            self::doSettingrolesvisibility($input, $record);
        }

        return $result;
        
    }

    public function Settingrolesvisibility($input) {

        $r = [];
        foreach($input['roles'] as $i => $item)
        {
            
            foreach(collect($item)->except(['role_id', 'slug'])->toArray() as $key => $data)
            {
                $input = [
                    'menu_id' => $input['menu_id'],
                    'role_id' => $item['role_id'],
                    'platform' => $key,
                    'visible' => $data['visible'],
                    'disabled' => $data['disabled'],
                ];

                $r[] = SysMenuRole::CreateOrUpdate($input);
            }
        }

        return $r;
    }

    /**
     * 14.06.2023
     * Returneaza meniurile definite in sistem
     * Se tine cont de platforma
     */
    public static function getMenus() {

        $user = \Auth::user();

        if( ! $user )
        {
            return [];
        }

        $r = [];

        foreach(self::whereIsRoot()->get() as $i => $item)
        {
            if(in_array(config('app.platform'), $item->platform))
            {
                // $r[$item->slug] = $item;
                $r[$item->slug] = [
                    'icon' => $item->icon,
                    'order_no' => $item->order_no,
                    'link' => $item->link,
                    'props' => $item->props,
                    'caption' => $item->caption,
                    'platform' => $item->platform,
                    'slug' => $item->slug,
                    'id' => $item->id,
                    'children' => $item->MakeMenu($user)
                ];
            }
        }

        return $r;
    }

    protected function MakeMenu($user) {
        $r = [];

        foreach($this->children as $i => $item)
        {
            $r[] = $item->MakeVisibility($user);
        }

        return $r;
    }

    protected function MakeVisibility($user) {

        if( config('app.platform') == 'admin')
        {   

            if( in_array('admin', $this->platform) )
            {
                $action_role = $this->roles()
                    ->wherePlatform(config('app.platform'))
                    ->where('role_id', $user->role->id)
                    ->first();

                $children = $this->MakeMenu($user);

                return [
                    'visible' => !! $action_role ? $action_role->attributes['visible'] : 0,
                    'disabled' => !! $action_role ? $action_role->attributes['disabled'] : 1,
                    'icon' => $this->icon,
                    'order_no' => $this->order_no,
                    'link' => $this->link,
                    'props' => $this->props,
                    'caption' => $this->caption,
                    'platform' => $this->platform,
                    'slug' => $this->slug,
                    'id' => $this->id,
                    'children' => $children,
                ];
            }
            
            return [];
        }

        return [];
    }

}