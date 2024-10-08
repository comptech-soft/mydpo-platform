<?php

namespace MyDpo\Models\Livrabile\Chestionare;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Models\Livrabile\Categories\Category;
use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;
use MyDpo\Traits\DaysDifference;
use MyDpo\Traits\Exportable;
use MyDpo\Traits\Importable;
use MyDpo\Rules\Nomenclatoare\Livrabile\Chestionare\Chestionar\UniqueName;
use MyDpo\Exports\Livrabile\Chestionare\Exporter;
use MyDpo\Imports\Livrabile\Chestionare\Importer;
use MyDpo\Models\Customer\Chestionare\CustomerChestionarUser;

class Chestionar extends Model {
    
    use Itemable, Actionable, DaysDifference, Exportable, Importable;

    protected $table = 'chestionare';

    protected $with = ['category'];

    protected $casts = [
        'id' => 'integer',
        'source_id' => 'integer',
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
        'source_id',
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

    protected static function GetExporter($input, $record) 
    {
        return new Exporter($input, $record); 
    }
    
    protected static function GetImporter($input) 
    {
        return new Importer($input); 
    }

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

        /** Chestionarul sursa */
        $source = self::find( $input['source_id']);

        /** Se construiesc datele noului chestionar */
        $target = self::PrepareSourceToDuplicate($source, $input);

        /** Chestionarul obtinut */
        $record = self::CreateDuplicatedChestionar($target);

        return $record;
    }

    public static function PrepareSourceToDuplicate(Chestionar $source, array $input)
    {
        $target = [
            ...$source->toArray(),
            ...$input,
            'source_id' => $source->id,
            'questions' => self::PrepareQuestionsToDuplicate(ChestionarQuestion::where('chestionar_id', $source->id)->whereNull('parent_id')->get()->toArray()),
        ];

        return $target;
    }

    public static function PrepareQuestionsToDuplicate(array $questions)
    {
        $r = [];

        foreach($questions as $question)
        {
            $r[] = self::PrepareOneQuestionsToDuplicate($question); 
        }

        return $r;
    }

    public static function PrepareOneQuestionsToDuplicate(array $question)
    {
        return [
            ...collect($question)->except(['options', 'children', '_lft', '_rgt', 'created_at', 'updated_at'])->toArray(),
            'source_id' => $question['id'],
            'id' => NULL,
            'options' => self::PrepareOptionsToDuplicate($question['options']),
            'children' => self::PrepareQuestionsToDuplicate($question['children']),
        ];
    }

    public static function PrepareOptionsToDuplicate(array $options)
    {
        return collect($options)->map( function($option){
            return [
                ...collect($option)->except(['created_at', 'updated_at'])->toArray(),
                'chestionar_question_id' => NULL,
                'id' => NULL,
                'source_id' => $option['id'], 
            ];
        })->toArray();
    }

    public static function CreateDuplicatedChestionar(array $target)
    {
        $input = collect($target)->except(['days_difference', 'visible', 'human_status', 'my_image', 'status', 'questions_count', 'questions', 'created_at', 'updated_at', 'created_by', 'updated_by', 'deleted_by', 'category'])->toArray();

        $record = self::create($input);

        self::CreateDuplicatedQuestions( $target['questions'], NULL, $record);

        return $record;
    }

    public static function CreateDuplicatedQuestions(array $questions, $parent, Chestionar $chestionar)
    {
        $r = [];
        
        foreach($questions as $question)
        {
            $r[] = self::CreateDuplicatedOneQuestion($question, $parent, $chestionar);
        }

        return $r;
    }

    public static function CreateDuplicatedOneQuestion(array $question, $parent, Chestionar $chestionar)
    {
        $input = [
            ...collect($question)->except(['options', 'children'])->toArray(),
            'chestionar_id' => $chestionar->id,
            'activate_on_answer_id' => NULL,
        ];

        if(! $parent)
        {
            $record = ChestionarQuestion::create($input);
        }
        else
        {
            $activator = $parent->options()->where('source_id', $question['activate_on_answer_id'])->first();

            if($activator)
            {
                $input['activate_on_answer_id'] = $activator->id;
            }

            $record = $parent->children()->create($input);
        }

        ChestionarQuestion::CreateDuplicatedOptions($question['options'], $record);

        self::CreateDuplicatedQuestions($question['children'], $record, $chestionar);

        return $record;
    }

    public static function CreateQuestionarFromImport($target)
    {
        $chestionar = self::create(collect($target)->except(['questions'])->toArray());

        $chestionar->attachImportedQuestions($target['questions'], NULL, 0);
    }

    public function attachImportedQuestions($questions, $parent, $level)
    {
        foreach($questions as $i => $question)
        {
            $this->attachOneImportedQuestions($question, $parent, $level);
        }
    }

    public function attachOneImportedQuestions($question, $parent, $level)
    {

        $input = [
            ...collect($question)->except(['children', 'options', 'parent_id'])->toArray(),
            'chestionar_id' => $this->id,
        ];

        if( ! $parent )
        {
            $node = ChestionarQuestion::create($input);
        }
        else
        {
            if(!! $input['activate_on_answer_id'])
            {
                $option = $parent->options()->where('answer_text', $input['activate_on_answer_id'])->first();

                if(!! $option)
                {
                    $input = [
                        ...$input,
                        'activate_on_answer_id' => $option->id,
                    ];
                }
            }
            
            
            $node = $parent->children()->create($input);
        }

        ChestionarQuestion::CreateDuplicatedOptions(collect($question['options'])->map(function($option, $i){
            return [
                'answer_text' => $option['answer_text'],
                'order_no' => 1 + $i,
                'id' => -1,
            ];
        })->toArray(), $node);

        if( count($question['children']) > 0)
        {
            $this->attachImportedQuestions($question['children'], $node, $level + 1);
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


    public function CalculateUserScores()
    {
        CustomerChestionarUser::CalculateAllUsersScores($this->id);
    }

    public static function CheckNameIfExists(string $name): string
    {
        $record = self::whereName($name)->first();
        
        if(!! $record)
        {
            return $name . '-' . \Carbon\Carbon::now()->format('Y-m-d-h-i-s');
        }

        return $name;
    }

    public static function GetRules($action, $input) {

        if(in_array($action, ['export', 'import', 'delete']) )
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