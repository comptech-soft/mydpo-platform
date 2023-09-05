<?php

namespace MyDpo\Models\Customer\ELearning;

use Illuminate\Database\Eloquent\Model;
// use MyDpo\Helpers\Performers\Datatable\GetItems;
// use MyDpo\Models\Curs;
// use MyDpo\Models\CustomerCursUser;
// use MyDpo\Models\CustomerCursFile;
// use MyDpo\Models\CustomerCursParticipant;
// use MyDpo\Models\Sharematerial;
// use MyDpo\Performers\CustomerCurs\GetSummary;
// use MyDpo\Performers\CustomerCurs\DesasociereUtilizatori;
// use MyDpo\Performers\CustomerCurs\DesasociereUsers;
// use MyDpo\Performers\CustomerCurs\StergereParticipant;
// use MyDpo\Performers\CustomerCurs\StergereFisier;

class CustomerCurs extends Model {

    protected $table = 'customers-cursuri';

    protected $casts = [
        'props' => 'json',
        'customer_id' => 'integer',
        'curs_id' => 'integer',
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
        'curs_id',
        'trimitere_id',
        'status',
        'effective_time',
        'assigned_users',
        'users_count',
        'users_count_sended',
        'users_count_started',
        'users_count_done',
        'files_count',
        'participants_count',
        'trimitere_number',
        'trimitere_date',
        'trimitere_sended_by',
        'props',
        'platform',
        'deleted',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    // protected $with = [
    //     'curs',
    //     'cursusers',
    // ];

    // public function curs() {
    //     return $this->belongsTo(Curs::class, 'curs_id');
    // }

    // public function trimitere() {
    //     return $this->belongsTo(Sharematerial::class, 'trimitere_id');
    // }

    // public function cursusers() {
    //     return $this->hasMany(CustomerCursUser::class, 'customer_curs_id');
    // }

    // public function cursfiles() {
    //     return $this->hasMany(CustomerCursFile::class, 'customer_curs_id');
    // }

    // public function cursparticipants() {
    //     return $this->hasMany(CustomerCursParticipant::class, 'customer_curs_id');
    // }

    // public static function getItems($input) {
    //     return (new GetItems(
    //         $input, 
    //         self::query()->has('curs'), 
    //         __CLASS__
    //     ))->Perform();
    // }

    // public static function desasociereUtilizatori($input) {
    //     $r = (new DesasociereUtilizatori($input))->Perform();
    //     self::syncUsersCounts($input['customer_id']);
    //     return $r;
    // }

    // public static function desasociereUsers($input) {
    //     $r = (new DesasociereUsers($input))->Perform();
    //     self::syncUsersCounts($input['customer_id']);
    //     return $r;
    // }

    // public static function stergereParticipant($input) {
    //     $r = (new StergereParticipant($input))->Perform();
    //     self::syncUsersCounts($input['customer_id']);
    //     return $r;
    // }

    // public static function stergereFisier($input) {
    //     $r = (new StergereFisier($input))->Perform();
    //     self::syncUsersCounts($input['customer_id']);
    //     return $r;
    // }

    // public static function getSummary($input) {
    //     return (new GetSummary($input))->Perform();
    // }

    // /**
    //  * actualizeaza numarul de utilizatori care au cursurile: sended, startde, done
    //  */
    // public static function syncUsersCounts($customer_id) {

    //     $records = self::where('customer_id', $customer_id)->get();

    //     foreach($records as $i => $record)
    //     {
    //         $record->files_count =  $record->cursfiles()->count();
    //         $record->participants_count =  $record->cursparticipants()->count();
    //         $record->trimitere_number =  $record->trimitere->number;
    //         $record->trimitere_date =  $record->trimitere->date;
    //         $record->trimitere_sended_by =  $record->trimitere->createdby->full_name;

    //         $record->users_count = 0;
    //         $record->users_count_sended = 0;
    //         $record->users_count_started = 0;
    //         $record->users_count_done = 0;

    //         $record->save();
    //     }


    //     $sql = "
    //         SELECT
    //             `customers-cursuri-users`.customer_curs_id,
    //             COUNT(*) AS users_count,
    //             SUM(IF(`customers-cursuri-users`.`status` = 'sended', 1, 0)) AS users_count_sended,
    //             SUM(IF(`customers-cursuri-users`.`status` = 'started', 1, 0)) AS users_count_started,
    //             SUM(IF(`customers-cursuri-users`.`status` = 'done', 1, 0)) AS users_count_done
    //         FROM `customers-cursuri-users`
    //         WHERE `customers-cursuri-users`.customer_id = " . $customer_id . "
    //         GROUP BY 1
    //     ";

    //     $results = \DB::select($sql);

    //     foreach($results as $i => $result)
    //     {
    //         $record = self::find($result->customer_curs_id);

    //         $record->users_count = $result->users_count;
    //         $record->users_count_sended = $result->users_count_sended;
    //         $record->users_count_started = $result->users_count_started;
    //         $record->users_count_done = $result->users_count_done;

    //         $record->save();
    //     }

        
    // }


    public static function CreateRecordsByTrimitere($trimitere) {
        $numberOfitems = $this->trimitere->count_users * $this->trimitere->count_materiale;
        $calculated_time = ($numberOfitems > 0) ? $this->trimitere->effective_time/$numberOfitems : 0; 

        foreach($this->trimitere->customers as $customer_id => $users)
        {
            foreach($users as $i => $user_id)
            {
                foreach($this->trimitere->materiale_trimise as $j => $curs_id)
                {
                    $input = [];

                    dd($input);
                }
            }
        }
    }

    
}