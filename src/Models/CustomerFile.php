<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Helpers\Performers\Datatable\GetItems;
use MyDpo\Helpers\Performers\Datatable\DoAction;
use MyDpo\Models\Folder;
use MyDpo\Models\Customer;
use MyDpo\Models\MaterialStatus;
use MyDpo\Models\User;
use MyDpo\Models\RoleUser;
use MyDpo\Performers\CustomerFile\ChangeFilesStatus;
use MyDpo\Performers\CustomerFile\MoveFiles;
use MyDpo\Performers\CustomerFile\DeleteFiles;
use MyDpo\Events\CustomerDocuments\FilesUpload as FilesUploadEvent;

class CustomerFile extends Model {

    
    protected $table = 'customers-files';

    protected $appends = ['icon', 'is_image', 'is_office', 'just_name'];

    protected $with = ['mystatus'];

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

    public function getIconAttribute() {
        return config('app.url') . '/imgs/extensions/'. strtolower($this->file_original_extension) . '.png';
    }

    public function getIsImageAttribute() {
        $ext = strtolower($this->file_original_extension);
        return in_array($ext, ['jpg', 'jpeg', 'png']);
    }   

    public function getIsOfficeAttribute() {
        $ext = strtolower($this->file_original_extension);
        return in_array($ext, ['doc', 'docx', 'xls', 'xlsx']);
    }  

    public function getJustNameAttribute() {
        return \Str::replace('.' . $this->file_original_extension, '', $this->file_original_name);
    } 

    function folder() {
        return $this->belongsTo(Folder::class, 'folder_id');
    }

    function mystatus() {
        return $this->belongsTo(MaterialStatus::class, 'status', 'slug');
    }

    public static function downloadFile($customer_id, $file_id) {

        $record = self::where('customer_id', $customer_id)->where('id', $file_id)->first();

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

    public static function getItems($input) {
        return (new GetItems(
            $input, 
            self::query()
            ->leftJoin(
                'customers-folders',
                function($j) {
                    $j->on('customers-folders.id', '=', 'customers-files.folder_id');
                }
            )
            ->whereRaw('((`customers-folders`.`deleted` IS NULL) OR (`customers-folders`.`deleted` = 0))')
            ->select('customers-files.*')
            ->with(['folder']),
            __CLASS__
        ))->Perform();
    }

    public static function moveFiles($input) {
        return (new MoveFiles($input))
            ->SetSuccessMessage('Mutare fi??iere cu success!')
            ->Perform();
    } 

    public static function changeFilesStatus($input) {
        return (new ChangeFilesStatus($input))
            ->SetSuccessMessage('Schimbare status cu success!')
            ->Perform();
    } 

    public static function deleteFiles($input) {
        return (new DeleteFiles($input))
            ->SetSuccessMessage('??tergere fi??iere cu success!')
            ->Perform();
    } 

    public static function GetRules($action, $input) {
        if( ($action == 'delete') || ($action == 'insert') )
        {
            return NULL;
        }
        $result = [
            'customer_id' => 'required|exists:customers,id',
            'file_original_extension' => 'required',
            'platform' => 'in:admin,b2b',
            'folder_id' => 'required|exists:customers-folders,id',
            'url' => 'required',
        ];
        return $result;
    }

    public static function doAction($action, $input) {
        if($action == 'update')
        {
            $input['file_original_name'] = $input['name'] . '.' . $input['file_original_extension'];
        }
        return (new DoAction($action, $input, __CLASS__))->Perform();
    }

    public static function CreateNotificationReceiversAdmin($input) {
        $user = \Auth::user();

        if($user->inRoles(['sa', 'admin']))
        {
            /**
             * 1.1. Admin face upload
             *       A. Admini           - toti adminii diferiti de cel care face upload
             *       B. Operatori        - operatorii care au clientul asociat
             *       C. Masteri          - toti masterii clientului
             *       D. Useri            - daca s-a urcat in structura la care el are acces
             */

            /** A. Admini           - toti adminii diferiti de cel care face upload */
            $admins = $user->GetMyAdmins(false);

            /** B. Operatori        - operatorii care au clientul asociat **/
            $operators = $input['customer']->GetMyOperators(true);

            /** C. Masteri          - toti masterii clientului */
            $masters = $input['customer']->GetMyMasters(true);

            /** D. Useri            - daca s-a urcat in structura la care el are acces */
            $users = $input['customer']->GetMyUserByFolderAccess($input['folder_id'], true);

            $users = [
                ...$admins,
                ...$operators,
                ...$masters,
                ...$users,
            ];            
        }
        else
        {
            if($user->inRoles(['operator']))
            {
                /**
                 * 1.2. Operator face upload
                 *       A. Admini           - toti adminii
                 *       B. Operatori        - operatorii care au clientul asociat, diferit de operatorul care face upload
                 *       C. Masteri          - toti masterii clientului
                 *       D. Useri            - daca s-a urcat in structura la care el are acces
                 */

                /** A. Admini           -  toti adminii */
                $admins = $user->GetMyAdmins(true);

                /** B. Operatori        - operatorii care au clientul asociat, diferit de operatorul care face upload **/
                $operators = $input['customer']->GetMyOperators(false);

                /** C. Masteri          - toti masterii clientului */
                $masters = $input['customer']->GetMyMasters(true);

                /** D. Useri            - daca s-a urcat in structura la care el are acces */
                $users = $input['customer']->GetMyUserByFolderAccess($input['folder_id'], true);

                $users = [
                    ...$admins,
                    ...$operators,
                    ...$masters,
                    ...$users,
                ];
            }  
            else
            {
                $users = [];
            }
        }
        
        return User::GetUsersByIds($users);
    }

    public static function CreateNotificationReceiversB2b($input) {

        $user = \Auth::user();

        $user = \Auth::user();

        $roleUser = RoleUser::where('user_id', $user->id)
            ->where('customer_id', $input['customer']->id)
            ->whereIn('role_id', [4, 5])
            ->first();

        if($roleUser->role->slug == 'master')
        {
            /**
             * 2.1. Master face upload
             *    A. Admini           - toti adminii
             *    B. Operatori        - operatorii care au clientul asociat
             *    C. Masteri          - toti masterii clientului, diferit de masterul care face upload
             *    D. Useri            - daca s-a urcat in structura la care el are acces
             */

            /** A. Admini           -  toti adminii */
            $admins = $user->GetMyAdmins(true);

            /** B. Operatori        - operatorii care au clientul asociat **/
            $operators = $input['customer']->GetMyOperators(true);

            /** C.Masteri          - toti masterii clientului, diferit de masterul care face upload */
            $masters = $input['customer']->GetMyMasters(false);

            /** D. Useri            - daca s-a urcat in structura la care el are acces */
            $users = $input['customer']->GetMyUserByFolderAccess($input['folder_id'], true);

            $users = [
                ...$admins,
                ...$operators,
                ...$masters,
                ...$users,
            ];
        }
        else
        {
            if($roleUser->role->slug == 'customer')
            {
                /**
                 * 2.2. User face upload
                 *   A. Admini           - toti adminii
                 *   B. Operatori        - operatorii care au clientul asociat
                 *   C. Masteri          - toti masterii clientului
                 *   D. Useri            - daca s-a urcat in structura la care el are acces
                 * 
                 */

                /** A. Admini           -  toti adminii */
                $admins = $user->GetMyAdmins(true);

                /** B. Operatori        - operatorii care au clientul asociat **/
                $operators = $input['customer']->GetMyOperators(true);

                /** C. Masteri          - toti masterii clientului */
                $masters = $input['customer']->GetMyMasters(true);

                /** D. Useri            - daca s-a urcat in structura la care el are acces */
                $users = $input['customer']->GetMyUserByFolderAccess($input['folder_id'], false);
               
            }
            else
            {
                $users = [];
            }
        }
            
        return User::GetUsersByIds($users);
    
    }

    public static function CreateNotifications($files, $input) {
        /**
         * REGULI TRIMITER NOTIFICARE upload-files
         * 
         * 1. Platforma MyDPOAdmin
         *      1.1. Admin face upload
         *              A. Admini           - toti adminii diferiti de cel care face upload
         *              B. Operatori        - operatorii care au clientul asociat
         *              C. Masteri          - toti masterii clientului
         *              D. Useri            - daca s-a urcat in structura la care el are acces
         *      1.2. Oerator face upload
         *              A. Admini           - toti adminii
         *              B. Operatori        - operatorii care au clientul asociat, diferit de operatorul care face upload
         *              C. Masteri          - toti masterii clientului
         *              D. Useri            - daca s-a urcat in structura la care el are acces
         * 2. Platforma MyDpo
         *      2.1. Master face upload
         *              A. Admini           - toti adminii
         *              B. Operatori        - operatorii care au clientul asociat
         *              C. Masteri          - toti masterii clientului, diferit de masterul care face upload
         *              D. Useri            - daca s-a urcat in structura la care el are acces
         *      2.2. User face upload
         *              A. Admini           - toti adminii
         *              B. Operatori        - operatorii care au clientul asociat
         *              C. Masteri          - toti masterii clientului
         *              D. Useri            - daca s-a urcat in structura la care el are acces
         */
        $method = 'CreateNotificationReceivers' . ucfirst(config('app.platform'));

        $receivers = call_user_func([__CLASS__, $method], $input);

        if(! $receivers )
        {
            return NULL;
        }

        foreach($files as $i => $file) 
        {
            foreach($receivers as $j => $receiver)
            {
                event(new FilesUploadEvent([
                    ...$input,
                    'file' => $file,
                    'receiver' => $receiver,
                ]));
            }
        }
    }

    public static function doInsert($input, $record) {
        if( ! array_key_exists('files', $input) )
        {
            throw new \Exception('Nu am fi??iere.');
        }

        if( ! is_array($input['files']) )
        {
            $input['files'] = [$input['files']];
        }

        $files = [];
        foreach($input['files'] as $file)
        {
            $record = self::ProcessFile($file, $input);

            if($record->status == 'public')
            {
                $files[] = $record;
            } 
        }

        $customer = Customer::find($input['customer_id']);

        self::CreateNotifications($files, [
            'customer' => $customer,
            'folder_id' => $input['folder_id'],
        ]);

        return $record;
    }

    public static function ProcessFile($file, $input) {
        
        $ext = strtolower($file->extension());

        if(in_array($ext, ['jpg', 'jpeg', 'png', 'doc', 'docx', 'xls', 'xlsx', 'pdf', 'txt', 'rar', 'zip']))
        {
            $filename = \Str::slug(str_replace($file->extension(), '', $file->getClientOriginalName())) . '.' .  strtolower($file->extension());
            
            $result = $file->storeAs('documents/' . $input['customer_id'] . '/' . \Auth::user()->id, $filename, 's3');

            $inputdata = [
                ...$input,
                'file_original_name' => $file->getClientOriginalName(),
                'file_original_extension' => $file->extension(),
                'file_full_name' => $filename,
                'file_mime_type' => $file->getMimeType(),
                'file_upload_ip' => request()->ip(),
                'file_size' => $file->getSize(),
                'url' => config('filesystems.disks.s3.url') . $result,
                'created_by' => \Auth::user()->id,
            ];

            if(in_array($ext, ['jpg', 'jpeg', 'png']))
            {
                $image = \Image::make($file);
                $inputdata = [
                    ...$inputdata,
                    'file_size' => $image->filesize(),
                    'file_width' => $image->width(),
                    'file_height' => $image->height(),
                ];
            }

            $exist = self::where('customer_id', $inputdata['customer_id'])
                ->where('folder_id', $inputdata['folder_id'])
                ->where('url', $inputdata['url'])
                ->first();

            if(! $exist )
            {
                return self::create($inputdata);
            }

            $exist->deleted = 0;
            $exist->save();

            return $exist;
        }
        else
        {
            throw new \Exception('Fi??ier incorect.');
        }
    }

}