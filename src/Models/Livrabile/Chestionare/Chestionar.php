<?php

namespace MyDpo\Models\Livrabile\Chestionare;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Models\Livrabile\Categories\Category;
use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;
use MyDpo\Traits\DaysDifference;
use MyDpo\Rules\Nomenclatoare\Livrabile\Chestionare\Chestionar\UniqueName;

class Chestionar extends Model {
    
    use Itemable, Actionable, DaysDifference;

    protected $table = 'chestionare';

    protected $with = ['category'];

    protected $casts = [
        'id' => 'integer',
        'props' => 'json',
        'category_id' => 'integer',
        'on_gap' => 'integer',
        'on_chestionare' => 'integer',
        'on_reevaluare' => 'integer',
        'questions_count' => 'integer',
        'deleted' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
        'visibility'=> 'integer',
    ];

    protected $fillable = [
        'id',
        'status',
        'name',
        'category_id',
        'questions_count',
        'visibility',
        'date_from',
        'date_to',
        'description',
        'on_chestionare',
        'on_gap',
        'on_reevaluare',
        'subject',
        'body',
        'deleted',
        'props',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $appends = [
        'visible',
        'days_difference',
        'human_status',
        'my_image',
    ];

    public function getVisibleAttribute() {
        return [
            'color' => !! $this->visibility ? 'green' : 'red',
            'icon' => !! $this->visibility ? 'mdi-check' : 'mdi-cancel',
        ];
    }

    public function getStatusAttribute() {
        if(! $this->date_from && ! $this->date_to )
        {
            return 2;
        }
        return $this->days_difference['hours'] > 0 ? 0 : 1;
    }

    public function getHumanStatusAttribute() {
        return self::$statuses[$this->status];
    }

    public function getMyImageAttribute() 
    {
        $image = config('app.url') . '/imgs/layout/card-chestionar-header.jpg';

        return $image;
    }
    
    public function category() {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public static function doDuplicate($input, $record) {

        /**
         * Chestionarul sursa
         */
        $source = self::find( $input['source_id']);

        dd($source);
        

        /**
         * Chestionarul obtinut
         */
        $record = self::create($input);

       

        /**
         * Start duplicare intrebari radacina
         * O intrebare radacina poate avea subintrebari
         * $record = chestionar
         */
        $record->duplicateQuestions($source, NULL);

        return $record;
    }

    public function duplicateOneQuestion($question, $parent_id)
    {
        $new_question = $question->replicate();

        $new_question->chestionar_id = $this->id;

        $new_question->save();

        $new_question->duplicateOptions();

        dd($new_question->children);

        $new_question->duplicateChildren();
    }


    public function duplicateQuestions(Chestionar $source, $parent_id)
    {
        /**
         * $this = chestionarul curent
         * Aflam interbarile chestionarului curent
         */
        if(! $parent_id)
        {
            $questions = ChestionarQuestion::where('chestionar_id', $source->id)->whereNull($parent_id)->get();
        }
        else
        {
            $questions = ChestionarQuestion::where('chestionar_id', $source->id)->where('parent_id', $parent_id)->get();
        }

        /**
         * Pentru fiecare intrebare din chestionarul curent
         * se face duplicarea ei
         */
        foreach($questions as $question)
        {
            $this->duplicateOneQuestion($question, $parent_id);
        }

    }

    public static function doDelete($input, $record) {

        $record->update([
            'deleted' => 1,
            'deleted_by'=> \Auth::user()->id,
            'name' => '#' . $record->id . '#',
            'props' => [
                ...(!! $record->props ? $record->props : []),
                'old_name' => $record->name, 
            ],
        ]);

        return $record;
    }

    public function syncQuestionCount()
    {
        $items = \DB::select("
            SELECT
                COUNT(*) AS questions_count
            FROM `chestionare-questions`
            WHERE (chestionar_id=" . $this->id . ") AND (parent_id IS NULL)"
        );

        $this->update(['questions_count' => $items[0]->questions_count]);
    }

    public static function getQuery() {
        return 
            self::query()
            ->leftJoin(
                'categories',
                function($j) {
                    $j->on('categories.id', '=', 'chestionare.category_id');
                }
            )
            ->select('chestionare.*')
        ;
    }

    public static function GetRules($action, $input) {

        if($action == 'delete')
        {
            return [];
        }

        $result = [
            'name' => [
                'required',
                new UniqueName($action, $input),
            ],
           
        ];
        return $result;
    }
    protected static $statuses = [
        0 => [
            'text' => 'Inactiv',
            'color' => 'red',
        ],

        1 => [
            'text' => 'Activ',
            'color' => 'blue',
        ],

        2 => [
            'text' => 'Activ (Nelimitat)',
            'color' => 'green',
        ],
    ];


}