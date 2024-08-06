<?php

namespace MyDpo\Models\Customer\Chestionare;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Helpers\Performers\Datatable\GetItems;
use MyDpo\Models\Chestionar;
// use MyDpo\Models\CustomerChestionarUser;
use MyDpo\Performers\CustomerChestionar\GetSummary;

class CustomerChestionar extends Model {

    protected $table = 'customers-chestionare';

    protected $casts = [
        'props' => 'json',
        'customer_id' => 'integer',
        'chestionar_id' => 'integer',
        'trimitere_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
        'deleted' => 'integer',
        'effective_time' => 'float',
        'assigned_users' => 'json',
    ];

    protected $fillable = [
        'id',
        'customer_id',
        'chestionar_id',
        'trimitere_id',
        'status',
        'platform',
        'effective_time',
        'props',
        'assigned_users',
        'users_count',
        'users_count_sended',
        'users_count_started',
        'users_count_done',
        'current_questions',
        'files_count',
        'participants_count',
        'trimitere_number',
        'trimitere_date',
        'trimitere_sended_by',
        'deleted',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $with = [
        // 'chestionar',
        // 'cursusers',
    ];

    public function chestionar() {
        return $this->belongsTo(Curs::class, 'chestionar_id');
    }

    // public function cursusers() {
    //     return $this->hasMany(CustomerCursUser::class, 'customer_curs_id');
    // }

    // public static function getItems($input) {
    //     return (new GetItems(
    //         $input, 
    //         self::query()->has('chestionar'), 
    //         __CLASS__
    //     ))->Perform();
    // }

    // public static function getSummary($input) {
    //     return (new GetSummary($input))->Perform();
    // }

    public static function CreateRecordsByTrimitere($trimitere) 
    {
        
        $numberOfitems = $trimitere->count_users * $trimitere->count_materiale;
        $calculated_time = ($numberOfitems > 0) ? $trimitere->effective_time/$numberOfitems : 0; 

        foreach($trimitere->customers as $customer_id => $users)
        {
            foreach($trimitere->materiale_trimise as $i => $chestionar_id)
            {
                $input = [
                    'customer_id' => $customer_id,
                    'chestionar_id' => $chestionar_id,
                    'trimitere_id' => $trimitere->id,
                    'platform'=> config('app.platform'),
                    'effective_time' => $calculated_time,
                    'assigned_users' => $users,
                    'trimitere_number' => $trimitere->number,
                    'trimitere_date' => $trimitere->date,
                    'trimitere_sended_by' => $trimitere->sender_full_name,
                ];

                self::CreateOrUpdateRecord($input);
            }

            self::Sync($customer_id);
        }
    }

    public function AttachUsersToChestionar() {

        foreach($this->assigned_users as $i => $user_id)
        {
            $input = [
                'customer_chestionar_id' => $this->id,
                'customer_id' => $this->customer_id,
                'chestionar_id' => $this->chestionar_id,
                'trimitere_id' => $this->trimitere_id,
                'user_id' => $user_id,
                'status' => 'sended',
                'platform'=> config('app.platform'),
            ];

            CustomerChestionarUser::AttachUser($input);
        }
    }

    public static function CreateOrUpdateRecord($input) {

        $record = self::where('customer_id', $input['customer_id'])->where('chestionar_id', $input['chestionar_id'])->first();
        
        if($record)
        {
            /**
             * Chestionarul este deja asociat la client
             */
            $record->update($input);
        }
        else
        {
            /**
             * Chestionarul nu este asociat la client ==> se asociaza
             */
            $record = self::create($input);
        }

        /**
         * Se asociaza si utilizatorii la inregistrarea creata
         */
        $record->AttachUsersToChestionar();
    }

    /**
     * actualizeaza numarul de utilizatori care au chestionarele: sended, started, done
     */
    public static function Sync($customer_id) {

        $records = self::where('customer_id', $customer_id)->get();

        foreach($records as $i => $record)
        {
            $record->files_count =  0; //$record->cursfiles()->count();
            $record->participants_count =  0; //$record->cursparticipants()->count();
            $record->trimitere_number =  $record->trimitere->number;
            $record->trimitere_date =  $record->trimitere->date;
            $record->trimitere_sended_by =  $record->trimitere->createdby->full_name;

            $record->users_count = $record->users_count_sended = $record->users_count_started = $record->users_count_done = 0;

            $record->save();
        }

        $results = \DB::select("
            SELECT
                `customers-chestionare-users`.customer_chestionar_id,
                COUNT(*) AS users_count,
                SUM(IF(`customers-chestionare-users`.`status` = 'sended', 1, 0)) AS users_count_sended,
                SUM(IF(`customers-chestionare-users`.`status` = 'started', 1, 0)) AS users_count_started,
                SUM(IF(`customers-chestionare-users`.`status` = 'done', 1, 0)) AS users_count_done
            FROM `customers-chestionare-users`
            WHERE `customers-chestionare-users`.customer_id = " . $customer_id . "
            GROUP BY 1
        ");

        foreach($results as $i => $result)
        {
            $record = self::find($result->customer_chestionar_id);

            $record->users_count = $result->users_count;
            $record->users_count_sended = $result->users_count_sended;
            $record->users_count_started = $result->users_count_started;
            $record->users_count_done = $result->users_count_done;

            $record->save();
        }

        /**
         * Se sterg inregistrarile care nu au utilizatori, fisiere si participanti
         */
        $records = self::where('customer_id', $customer_id)
            ->where('users_count', 0)
            ->where('files_count', 0)
            ->where('participants_count', 0)
            ->delete();
    }
}