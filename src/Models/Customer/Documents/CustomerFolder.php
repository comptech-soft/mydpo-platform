<?php

namespace MyDpo\Models\Customer\Documents;

use Kalnoy\Nestedset\NodeTrait;
use MyDpo\Models\Authentication\UserSetting;
use MyDpo\Models\Customer\Customer;
use MyDpo\Models\Authentication\User;
use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;
use MyDpo\Rules\Customer\Livrabile\Documentsable\Folders\UniqueName;

class CustomerFolder extends Folder {

    use NodeTrait, Itemable, Actionable; 
    
    protected $with = [ 
        'children',
        'files',
        'customer',
        'creator',
    ];
    
    function files() {        
        $platform = config('app.platform');

        if($platform == 'admin')
        {
            return $this->hasMany(CustomerFile::class, 'folder_id');
        }

        return $this->hasMany(CustomerFile::class, 'folder_id')
            ->whereRaw("((`customers-files`.`platform` = 'b2b') OR ( (`customers-files`.`platform` = 'admin') AND (`customers-files`.`status` <> 'protected')))");
    }

    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id')->select(['id', 'name', 'logo', 'status', 'city_id']);
    }

    function creator() {
        return $this->belongsTo(User::class, 'created_by')->select(['id', 'last_name', 'first_name', 'email', 'avatar']);
    }

    public static function doPermission($input, $action) {
        return CustomerFolderPermission::UpdatePermissions($input);
    }
    
    public static function doSaveorder($input, $record) {
        
        $code = $input['platform'] . '-' . $input['customer_id'] . '-customer-' . ($input['type'] == 'documente' ? 'folders' : $input['type']) . '-order';

        UserSetting::saveSetting([
            'user_id' => $input['user_id'], 
            'platform' => $input['platform'],
            'customer_id' => $input['customer_id'], 
            'code' =>  $code,
            'value' => $input['items'],
        ]);  

    }

    function DeleteWithFiles() {

        foreach($this->files as $file)
        {
            $file->delete();
        }
        
        $this->deleted = 1;
        $this->save();

        foreach($this->children as $child)
        {
            $child->DeleteWithFiles();
        }
    }

    public static function doDelete($input, $folder) {
        $folder->DeleteWithFiles();
        return $folder;
    }

    public static function doInsert($input, $folder) {

        if( ! $input['parent_id'] )
        {
            $folder = self::create($input);
        }
        else
        {
            $parent = self::find($input['parent_id']);
            $folder = $parent->children()->create($input);
        }
    
        return $folder;
    }

    public static function GetRules($action, $input) {
        if( ! in_array($action, ['insert', 'update']) )
        {
            return NULL;
        }
        $result = [
            'customer_id' => 'required|exists:customers,id',
            'platform' => 'in:admin,b2b',
            'type' => 'in:documente,analizagap,infografice,sfaturidpo,studiicaz',
            'name' => [
                'required',
                new UniqueName($action, $input),
            ],
        ];
        return $result;
    }

    public static function GivePermissionsToTrees( $folders, $user_id, $customer_id)
    {
        foreach($folders as $folder)
        {
            self::GivePermissionsToOneTree($folder, $user_id, $customer_id);
        }
    }

    public static function GivePermissionsToOneTree( $folder, $user_id, $customer_id )
    {
        CustomerFolderPermission::GivePermissionToUser($folder->id, $user_id, $customer_id);

        dd($folder->children);
    }

}