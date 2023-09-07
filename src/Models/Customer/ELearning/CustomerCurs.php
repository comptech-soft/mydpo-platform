<?php

namespace MyDpo\Models\Customer\ELearning;

use Illuminate\Database\Eloquent\Model;

use MyDpo\Models\Nomenclatoare\Livrabile\ELearning\Curs;
use MyDpo\Models\Nomenclatoare\Livrabile\ELearning\Sharematerial;

use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;

// use MyDpo\Models\CustomerCursUser;
// use MyDpo\Models\CustomerCursFile;
// use MyDpo\Models\CustomerCursParticipant;
// use MyDpo\Performers\CustomerCurs\GetSummary;
// use MyDpo\Performers\CustomerCurs\DesasociereUtilizatori;
// use MyDpo\Performers\CustomerCurs\DesasociereUsers;
// use MyDpo\Performers\CustomerCurs\StergereParticipant;
// use MyDpo\Performers\CustomerCurs\StergereFisier;

class CustomerCurs extends Model {

    use Itemable, Actionable;

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

    protected $with = [
        'curs',
        'cursusers',
        'trimitere',
    ];

    public function curs() {
        return $this->belongsTo(Curs::class, 'curs_id');
    }

    public function trimitere() {
        return $this->belongsTo(Sharematerial::class, 'trimitere_id');
    }

    public function cursusers() {
        return $this->hasMany(CustomerCursUser::class, 'customer_curs_id');
    }

    public function cursfiles() {
        return $this->hasMany(CustomerCursFile::class, 'customer_curs_id');
    }

    public function cursparticipants() {
        return $this->hasMany(CustomerCursParticipant::class, 'customer_curs_id');
    }

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

    public static function doAsociere($input, $record) {
        return Sharematerial::doInsert($input, $record);;
    }

    public static function GetQuery() {
        return 
            self::query()
            ->leftJoin(
                'cursuri',
                function($q) {
                    $q->on('cursuri.id', '=', 'customers-cursuri.curs_id');
                }
            )
            ->leftJoin(
                'share-materiale',
                function($q) {
                    $q->on('share-materiale.id', '=', 'customers-cursuri.trimitere_id');
                }
            )
            // ->leftJoin(
            //     'customers-departamente',
            //     function($q) {
            //         $q->on('customers-departamente.id', '=', 'customers-persons.department_id');
            //     }
            // )
            ->whereRaw("((`customers-cursuri`.`deleted` IS NULL) OR (`customers-cursuri`.`deleted` = 0))")
            ->select('customers-cursuri.*');
    }

    public static function CreateRecordsByTrimitere($trimitere) {
        $numberOfitems = $trimitere->count_users * $trimitere->count_materiale;
        $calculated_time = ($numberOfitems > 0) ? $trimitere->effective_time/$numberOfitems : 0; 

        foreach($trimitere->customers as $customer_id => $users)
        {
            foreach($trimitere->materiale_trimise as $i => $curs_id)
            {
                $input = [
                    'customer_id' => $customer_id,
                    'curs_id' => $curs_id,
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

    public static function CreateOrUpdateRecord($input) {

        $record = self::where('customer_id', $input['customer_id'])->where('curs_id', $input['curs_id'])->first();
        
        if($record)
        {
            /**
             * Cursul este deja asociat la client
             */
            $record->update($input);
        }
        else
        {
            /**
             * Cursul nu este asociat la client ==> se asociaza
             */
            $record = CustomerCurs::create($input);
        }

        /**
         * Se asociaza si utilizatorii la inregistrarea creata
         */
        $record->AttachUsersToCurs();
    }

    public function AttachUsersToCurs() {

        foreach($this->assigned_users as $i => $user_id)
        {
            $input = [
                'customer_curs_id' => $this->id,
                'customer_id' => $this->customer_id,
                'curs_id' => $this->curs_id,
                'trimitere_id' => $this->trimitere_id,
                'user_id' => $user_id,
                'status' => 'sended',
                'platform'=> config('app.platform'),
            ];

            CustomerCursUser::AttachUser($input);
        }

    }

    /**
     * actualizeaza numarul de utilizatori care au cursurile: sended, started, done
     */
    public static function Sync($customer_id) {

        $records = self::where('customer_id', $customer_id)->get();

        foreach($records as $i => $record)
        {
            $record->files_count =  $record->cursfiles()->count();
            $record->participants_count =  $record->cursparticipants()->count();
            $record->trimitere_number =  $record->trimitere->number;
            $record->trimitere_date =  $record->trimitere->date;
            $record->trimitere_sended_by =  $record->trimitere->createdby->full_name;

            $record->users_count = $record->users_count_sended = $record->users_count_started = $record->users_count_done = 0;

            $record->save();
        }

        $results = \DB::select("
            SELECT
                `customers-cursuri-users`.customer_curs_id,
                COUNT(*) AS users_count,
                SUM(IF(`customers-cursuri-users`.`status` = 'sended', 1, 0)) AS users_count_sended,
                SUM(IF(`customers-cursuri-users`.`status` = 'started', 1, 0)) AS users_count_started,
                SUM(IF(`customers-cursuri-users`.`status` = 'done', 1, 0)) AS users_count_done
            FROM `customers-cursuri-users`
            WHERE `customers-cursuri-users`.customer_id = " . $customer_id . "
            GROUP BY 1
        ");

        foreach($results as $i => $result)
        {
            $record = self::find($result->customer_curs_id);

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