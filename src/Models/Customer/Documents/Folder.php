<?php

namespace MyDpo\Models\Customer\Documents;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Models\Customer\Customer_base as Customer;

use MyDpo\Scopes\NotdeletedScope;

class Folder extends Model  {

    protected $table = 'customers-folders';

    protected $casts = [
        'id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
        'customer_id' => 'integer',
        'default_folder_id' => 'integer',
        'deleted' => 'integer',
        'props'=> 'json',
        'parent_id' => 'integer',
        'order_no' => 'integer',
    ];

    protected $fillable = [
        'id',
        'name',
        'platform',
        'default_folder_id',
        'customer_id',
        'deleted',
        'parent_id',
        'order_no',
        'type',
        'props',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected static function booted() {
        static::addGlobalScope( new NotdeletedScope() );
    }

    public static function CreateDefaultFolders($customer_id) {
        $customer = Customer::find($customer_id);
        $defaultFolders = CustomerFolderDefault::whereNull('parent_id')->get();

        foreach($defaultFolders as $i => $defaultFolder) 
        {
            self::CreateDefaultFolder($customer_id, $defaultFolder, NULL);
        }

        $customer->default_folders_created = 1;
        $customer->save();
    }

    public static function CreateDefaultFolder($customer_id, $defaultFolder, $parent) {


        $folder = CustomerFolder::where('customer_id', $customer_id)->where('name', $defaultFolder->name);

        if(!! $parent )
        {
            $folder = $folder->where('parent_id', $parent->id);
        }

        $folder = $folder->first();

        $input = [
            'name' => $defaultFolder->name,
            'customer_id' => $customer_id,
            'default_folder_id' => $defaultFolder->id,
            'platform' => 'admin',
            'props' => [
                'defaultfolder' => $defaultFolder, 
            ],
            'order_no' => $defaultFolder->id == 11 ? 32767 : $defaultFolder->id,
            'deleted' => 0,
            'created_by' => \Auth::user()->id,
        ];

        if( ! $folder )
        {
            if(! $parent )
            {
                $folder = CustomerFolder::create($input);
            }
            else
            {
                $parent->children()->create($input);
            }  
        }
        else
        {
            $folder->update($input);
        }

        if($defaultFolder->children->count())
        {
            foreach($defaultFolder->children as $i => $child) 
            {
                self::CreateDefaultFolder($customer_id, $child, $folder);
            }
        }
    }

    public static function CreateInfograficeFolder($customer_id) {

        $folder = self::where('customer_id', $customer_id)
            ->where('name', 'Infografice')
            ->where('platform', 'admin')
            ->where('type', 'infografice')
            ->whereNull('parent_id')
            ->where('deleted', 0)
            ->first();

        if(! $folder)
        {
            $folder = self::create([
                'customer_id' => $customer_id,
                'name' => 'Infografice',
                'platform' => 'admin',
                'type' => 'infografice',
                'parent_id' => NULL,
                'deleted' => 0,
            ]);
        }

        return $folder;
    }

}