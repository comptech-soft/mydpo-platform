<?php

namespace MyDpo\Models\Customer\Documents;

use Illuminate\Database\Eloquent\Model;

use MyDpo\Models\Customer\Customer;
use MyDpo\Models\Customer\ELearning\MaterialStatus;
use MyDpo\Models\Authentication\User;
use MyDpo\Models\Customer\Accounts\Account;
use MyDpo\Models\Customer\Teams\Team;
use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;

class CustomerFile extends Model {
    
    use Itemable, Actionable; 

    protected $table = 'customers-files';
    
    protected $casts = [
        'id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
        'deleted' => 'integer',
        'customer_id' => 'integer',
        'folder_id' => 'integer',
        'props' => 'json',
    ];

    protected $fillable = [
        'id',
        'customer_id',
        'folder_id',
        'file_original_name',
        'file_original_extension',
        'file_full_name',
        'file_mime_type',
        'file_upload_ip',
        'url',
        'file_size',
        'file_width',
        'file_height',
        'name',
        'deleted',
        'platform',
        'description',
        'status',
        'props',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $appends = [
        'is_image', 
        'is_office',
        'is_pdf',
        'just_name',
        'icon'
    ];

    protected $with = [
        'mystatus',
        'creator',
        'folder',
        'customer',
    ];

    public function getIsImageAttribute() {
        return in_array(strtolower($this->file_original_extension), ['jpg', 'jpeg', 'png']);
    }   

    public function getIsOfficeAttribute() {
        return in_array(strtolower($this->file_original_extension), ['doc', 'docx', 'xls', 'xlsx']);
    }  

    public function getIsPdfAttribute() {
        return in_array(strtolower($this->file_original_extension), ['pdf']);
    } 

    public function getIconAttribute() {
        return config('app.url') . '/imgs/extensions/'. strtolower($this->file_original_extension) . '.png';
    }

    public function getJustNameAttribute() {
        return \Str::replace('.' . $this->file_original_extension, '', $this->file_original_name);
    } 

    function folder() {
        return $this->belongsTo(Folder::class, 'folder_id')->select(['id', 'name', 'type']);
    }

    function mystatus() {
        return $this->belongsTo(MaterialStatus::class, 'status', 'slug');
    }

    function creator() {
        return $this->belongsTo(User::class, 'created_by')->select(['id', 'last_name', 'first_name', 'email', 'avatar']);
    }

    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id')->select(['id', 'name', 'logo', 'status', 'city_id']);
    }

    public static function doDownload($id) {

        $record = self::where('id', $id)->first();

        if(!! $record )
        {
            $path = $record->url;
            if(\Str::contains($path, 'decalexb2b') )
            {
                $path = \Str::replace(config('filesystems.disks.s3old.url'), '', $path);
                return \Storage::disk('s3old')->download($path, $record->file_original_name);
            }
            $path = \Str::replace(config('filesystems.disks.s3.url'), '', $path);
            return \Storage::disk('s3')->download($path, $record->file_original_name);
        }

        return NULL;
    }

    /**
     * Download un grup de fisiere
     */
    public static function doDownloadfiles($input) {

        $files = self::whereIn('id', $input['files_ids'])->get()->map(function($item) {
            return $item->url;
        })->toArray();

        $temp_directory = storage_path('temp');

        // Verifică și creează directorul temporar dacă nu există
        if (! \File::exists($temp_directory)) 
        {
            \File::makeDirectory($temp_directory, 0755, true);
        }
        
        foreach($files as $url) 
        {
            $fileName = basename($url);
            $fileContents = \Storage::disk('s3')->get(\Str::replace( config('filesystems.disks.s3.url'), '', $url));
            file_put_contents("$temp_directory/$fileName", $fileContents);
        }

        $zip = new \ZipArchive();

        $zipFileName = 'arhiva.zip';

        if ($zip->open($zipFileName, \ZipArchive::CREATE) === TRUE) 
        {
            // Adaugă fișierele descărcate în arhiva ZIP
            $files = glob("$temp_directory/*");
            
            foreach ($files as $file) 
            {
                $zip->addFile($file, basename($file));
            }
    
            $zip->close();
            
            // Șterge fișierele inițiale
            foreach ($files as $file) 
            {
                unlink($file);
            }

            return [
                'url' => asset($zipFileName),
            ];

        } 
       
        throw new \Exception('Nu am putut crea arhiva ZIP.');        
    }

    public static function doMove($input, $record) {

        foreach($input['files_ids'] as $i => $file_id)
        {
            $data = [
                'id' => $file_id,
                'customer_id' => $input['customer_id'],
                'folder_id' => $input['folder_id'],
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d'),
                'updated_by' => \Auth::user()->id,
            ];

            self::MoveFile($data);
        }

        return [
            'folder_id' => $input['folder_id'],
        ];
    }

    public static function doStatus($input, $record) {

        foreach($input['files_ids'] as $i => $file_id)
        {
            $record = self::find($file_id);

            $record->status = $input['status'];

            $record->save();

            if($record->status == 'public')
            { 
                event(new \MyDpo\Events\Customer\Livrabile\Documents\UploadFile('upload.file', [
                    'nume_fisier' => $record->file_original_name,
                    'nume_folder' => $record->folder->name,
                    'customers' => self::CreateUploadReceivers($record->customer_id, $record->folder_id), 
                    'link' => '/' . $record->folder->page_link . '/' . $input['customer_id'] . '?folder_id=' . $record->folder_id,
                ]));
            }
        }

        return [
            'folder_id' => $input['folder_id'],
        ];
    }

    public static function doDelete($input, $record) {
        foreach($input['files_ids'] as $i => $file_id)
        {
            $record = self::find($file_id);
            
            event(new \MyDpo\Events\Customer\Livrabile\Documents\UploadFile('delete.file', [
                'nume_fisier' => $record->file_original_name,
                'nume_folder' => $record->folder->name,
                'customers' => self::CreateUploadReceivers($record->customer_id, $record->folder_id), 
                'link' => '/' . $record->page_link . '/' . $input['customer_id'] . '?folder_id=' . $record->folder_id,
            ]));

            $record->delete();
        }

        return [
            'folder_id' => $input['folder_id'],
        ];
    }

    public static function MoveFile($input) {
        /**
         * Fisierul care se muta
         */
        $original = self::where('id', $input['id'])->first();
        $nume_folder_sursa = $original->folder->name;

        $record = self::where('customer_id', $input['customer_id'])
            ->where('folder_id', $input['folder_id'])
            ->whereUrl($original->url)
            ->whereDeleted(0)
            ->first();

        if(!! $record )
        {
            $original->delete();
        }
        else
        {
            $original->update($input);
            $original->refresh();

            if($original->status == 'public')
            {    
                event(new \MyDpo\Events\Customer\Livrabile\Documents\UploadFile('move.file', [
                    'nume_fisier' => $original->file_original_name,
                    'nume_folder_dest' => $original->folder->name,
                    'nume_folder_sursa' => $nume_folder_sursa,
                    'customers' => self::CreateUploadReceivers($original->customer_id, $original->folder_id), 
                    'link' => '/' . $original->folder->page_link . '/' . $input['customer_id'] . '?folder_id=' . $original->folder_id,
                ]));
            }
            
        }
    }
    
    public static function doUpdate($input, $record) {
        $file_original_name = \Str::replace($record->file_original_extension, '', $input['file_original_name']) . '.' . $record->file_original_extension;

        $input = [
            ...$input,
            'file_original_name' => $file_original_name,
        ];

        $record->update($input);

        return $record;
    }

    public static function doInsert($input, $record) {

        foreach($input['files'] as $file)
        {
            self::CreateFile($file, $input);
        }

        return [
            'folder_id' => $input['folder_id'],
        ];
    }

    public static function CreateFile($file, $input) {
        
        if(in_array($ext = $file->extension(), ['jpg', 'jpeg', 'png', 'doc', 'docx', 'xls', 'xlsx', 'pdf', 'txt', 'rar', 'zip']))
        {
            $filename = \Str::slug(str_replace($file->extension(), '', $file->getClientOriginalName())) . '.' .  strtolower($file->extension());
            
            $result = $file->storeAs('documents/' . $input['customer_id'] . '/' . \Auth::user()->id, $filename, 's3');

            $input = [
                ...$input,
                'file_original_name' => $file->getClientOriginalName(),
                'file_original_extension' => $file->extension(),
                'file_full_name' => $filename,
                'file_mime_type' => $file->getMimeType(),
                'file_upload_ip' => request()->ip(),
                'file_size' => $file->getSize(),
                'url' => config('filesystems.disks.s3.url') . $result,
                'created_by' => \Auth::user()->id,
                'deleted' => 0,
            ];

            if(in_array($ext, ['jpg', 'jpeg', 'png']))
            {
                $image = \Image::make($file);
                $input = [
                    ...$input,
                    'file_size' => $image->filesize(),
                    'file_width' => $image->width(),
                    'file_height' => $image->height(),
                ];
            }

            $record = self::where('customer_id', $input['customer_id'])
                ->where('folder_id', $input['folder_id'])
                ->where('url', $input['url'])
                ->first();

            if(! $record )
            {
                $record = self::create($input);
            }
            else
            {
                $record->update([...$input, 'id' => $record->id]);
            }

            if($input['status'] == 'public')
            {
                event(new \MyDpo\Events\Customer\Livrabile\Documents\UploadFile('upload.file', [
                    'nume_fisier' => $record->file_original_name,
                    'nume_folder' => $record->folder->name,
                    'customers' => self::CreateUploadReceivers($input['customer_id'], $input['folder_id']), 
                    'link' => '/' . $record->folder->page_link . '/' . $input['customer_id'] . '?folder_id=' . $record->folder_id,
                ]));
            }
        }
        else
        {
            throw new \Exception('Fișier incorect.');
        }
    }

    /**
     * Toti utilizatorii care primesc notificari
     */
    public static function CreateUploadReceivers($customer_id, $folder_id) {
        $r = [];

        /**
         * Toate conturile atasate clientului
         */
        foreach(Account::where('customer_id', $customer_id)->get() as $i => $account)
        {

            if($account->role_id == 4)
            {
                /**
                 * Contul este de master
                 */
                if( ! in_array($account->user_id, $r) )
                {
                    $r[] = $account->user_id;
                }
            }
            else
            {
                if($account->role_id == 5)
                {
                    /**
                     * Contul este de user
                     * Verific daca are acces pe folderul respectiv
                     */

                    $folder_permission = CustomerFolderPermission::where('customer_id', $customer_id)
                        ->where('user_id',  $account->user_id)
                        ->where('folder_id',  $folder_id)
                        ->first();
                   
                    if(!! $folder_permission && ($folder_permission->has_access == 1))
                    {
                        if( ! in_array($account->user_id, $r) )
                        {
                            $r[] = $account->user_id;
                        }
                    } 
                }
            }
        }

        /**
         * Toti adminii
         */
        foreach(User::whereType('admin')->get() as $i => $user)
        {
            if( ! in_array($user->id, $r) )
            {
                $r[] = $user->id;
            }
        }

        /**
         * Team-ul
         */
        foreach(Team::where('customer_id', $customer_id)->get() as $i => $member)
        {
            if( ! in_array($member->user_id, $r) )
            {
                $r[] = $member->user_id;
            }
        }

        return collect($r)->map(function($user_id) use ($customer_id){
            return $customer_id . '#' . $user_id;
        })->toArray();
    }

    public static function GetQuery() {
        return 
            self::query()
            ->leftJoin(
                'customers-folders',
                function($q) {
                    $q->on('customers-folders.id', '=', 'customers-files.folder_id');
                }
            )
            ->select('customers-files.*');
    }

}