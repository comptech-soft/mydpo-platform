<?php

namespace MyDpo\Models\Customer\Documents;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Traits\Itemable;

class CustomerFolderPermission extends Model {

    use Itemable;
    
    protected $table = 'customers-folders-permissions';
    
    protected $casts = [
        'id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'customer_id' => 'integer',
        'folder_id' => 'integer',
        'user_id' => 'integer',
        'has_access' => 'integer',
    ];

    protected $fillable = [
        'id',
        'customer_id',
        'folder_id',
        'user_id',
        'has_access',
        'updated_by',
        'deleted_by',
        'created_by',
    ];

    public function folder() {
        return $this->belongsTo(Folder::class, 'folder_id');
    }

    /**
     * Setarea permisiunilor pe un folder
     */
    public static function UpdatePermissions($input) {
        
        self::where('customer_id',$input['customer_id'])
            ->where('user_id',  $input['user_id'])
            ->update([
                'has_access' => 0,
            ]);

        if( ! array_key_exists('folders_ids', $input ) )
        {
            $input['folders_ids'] = [];
        }

        foreach($input['folders_ids'] as $i => $folder_id) 
        {
            self::MakeAccess($folder_id, $input['customer_id'], $input['user_id']);
        }

        dd($input);
       
        event(new \MyDpo\Events\Customer\Livrabile\Documents\FolderPermissions('folder.permissions', [
            'foldere' => 'bbb',
            'customers' => 'ccc', 
            'link' => $input['pathname'],            
        ]));

    }

    public static function MakeAccess($folder_id, $customer_id, $user_id) {

        $record = self::where('customer_id', $customer_id)
            ->where('user_id',  $user_id)
            ->where('folder_id',  $folder_id)
            ->first();

        if(!! $record)
        {
            $record->update([
                'has_access' => 1,
                'updated_by' => \Auth::user()->id,
            ]);
        }
        else
        {
            self::create([
                'has_access' => 1,
                'customer_id' => $customer_id,
                'user_id' => $user_id,
                'folder_id' => $folder_id,
                'created_by' => \Auth::user()->id,
            ]);
        }

        $folder = CustomerFolder::find($folder_id);

        if(!! $folder->parent_id)
        {
            self::MakeAccess($folder->parent_id, $customer_id, $user_id);
        }

    }
    
}