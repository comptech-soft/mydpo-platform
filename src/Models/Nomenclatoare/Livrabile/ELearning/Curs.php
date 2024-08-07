<?php

namespace MyDpo\Models\Nomenclatoare\Livrabile\ELearning;

use Illuminate\Database\Eloquent\Model;

// use MyDpo\Scopes\NotdeletedScope;

use Illuminate\Http\UploadedFile;
use MyDpo\Models\Livrabile\Categories\Category;

use MyDpo\Performers\Curs\GetKnolyxCourses;
// use MyDpo\Models\Livrabile\ELearning\Knolyx;
use MyDpo\Rules\Nomenclatoare\Livrabile\Cursuri\Curs\IsUrlPresent;
use MyDpo\Rules\Nomenclatoare\Livrabile\Cursuri\Curs\IsFilePresent;
use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;
use MyDpo\Traits\DaysDifference;


class Curs extends Model {

    use Itemable, Actionable, DaysDifference;

    protected $table = 'cursuri';
    
    protected $casts = [
        'id' => 'integer',
        'props' => 'json',
        'curs_status' => 'json',
        'category_id' => 'integer',
        'visibility' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
        'adresare_id' => 'integer',
        'deleted' => 'integer',
        'tematica' => 'json',
        'preview_image' => 'json',
        'file' => 'json',
        'k_avatar' => 'json',
    ];

    protected $fillable = [
        'id',
        'category_id',
        'adresare_id',
        'preview_image',
        'name',
        'type',
        'tip',
        'descriere',
        'tematica',
        'visibility',
        'url',
        'date_from',
        'date_to',
        'props',
        'public_private',
        'curs_status',
        'file',
        'k_id',
        'k_level',
        'k_duration',
        'k_number_students_enrolled',
        'k_from_training_tracker',
        'k_avatar',
        'deleted',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

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

    protected $appends = [
        'visible',
        'days_difference',
        'my_url',
        'my_image',
        'status'
    ];

    protected $with = [
        'category', 
        'adresare',
        'sursa'
    ];

    // protected static function booted() {
    //     static::addGlobalScope( new NotdeletedScope() );
    // }

    /**
     * 
     * ATTRIBUTES
     * 
     */
    public function getStatusAttribute() {
        if(! $this->date_from && ! $this->date_to )
        {
            return 2;
        }
        return $this->days_difference['hours'] > 0 ? 0 : 1;
    }

    public function getMyUrlAttribute() {
        if( ($this->type == 'knolyx') && $this->k_id)
        {
            return config('knolyx.url') . $this->k_id;
        }

        if( ($this->type == 'fisier') && $this->file)
        {   
            return $this->file['url'];
        }

        return $this->url;
    }

    public function getMyImageAttribute() {

        if( $this->preview_image )
        {
            return $this->preview_image['url'];
        }

        if( $this->type == 'youtube')
        {
            $code= \Str::of($this->url)->explode('=')->last();
            return 'https://img.youtube.com/vi/' . $code . '/0.jpg';
        }

        $image = config('app.url') . '/imgs/layout/card-course-header.jpg';

        if(! $this->props )
        {
            return $image;
        }

        if( ! array_key_exists('image', $this->props))
        {
            return $image;
        }

        if( ($this->type == 'knolyx') && $this->k_id)
        {
            return "data:" . $this->props['image']['mime'] . ";base64," . $this->props['image']['base64'];
        }

        return $image;
    }

    /**
     * Atributul visible pentru interfata
     */
    public function getVisibleAttribute() {
        return [
            'color' => !! $this->visibility ? 'green' : 'red',
            'icon' => !! $this->visibility ? 'mdi-check' : 'mdi-cancel',
        ];
    }
    
    /**
     * RELATIONS
     */
    public function category() {
        return $this->belongsTo(Category::class, 'category_id')->select(['id', 'name']);
    }

    public function adresare() {
        return $this->belongsTo(Adresare::class, 'adresare_id')->select(['id', 'name']);
    }

    public function sursa() {
        return $this->belongsTo(Type::class, 'type', 'slug')->select(['id', 'slug', 'name']);
    }

    // // public function customercursuri() {
    // //     return $this->hasMany(CustomerCurs::class, 'curs_id');
    // // }

    // // public function fisiere() {
    // //     return $this->hasMany(CursFisier::class, 'curs_id');
    // // }

    // public static function PrepareActionInput($action, $input) {
    //     if(! array_key_exists('tematica', $input) )
    //     {
    //         $input['tematica'] = NULL;
    //     }
    //     else
    //     {
    //         if(! is_array($input['tematica']) )
    //         {
    //             $input['tematica'] = NULL;
    //         }
    //         else
    //         {
    //             if( count($input['tematica']) == 0)
    //             {
    //                 $input['tematica'] = NULL;
    //             }
    //         }
    //     }
    //     return $input;
    // }

    // public static function getKnolyxCoursesImages() {

    //     $courses = Curs::whereNotNull('k_id')->get();

    //     foreach($courses as $i => $curs)
    //     {
    //         $curs->getKnolyxImage();
    //     }

    // }

    // public function getKnolyxImage() {
    //     $image = Knolyx::getCourseImage($this->k_id);

    //     if( ! $this->props )
    //     {
    //         $this->props = [];
    //     }

    //     if($image)
    //     {
    //         $this->props = [
    //             ...$this->props,
    //             'image' => $image,
    //         ];
    //     }

    //     $this->save();
    // }

    public static function getKnolyxCourses() {
        return (new GetKnolyxCourses([]))->Perform();
    }

    public static function saveCoursesFromKnolyx($courses) {
        foreach($courses as $i => $course)
        {
            self::saveCourseFromKnolyx($course);
        }
    }

    public static function saveCourseFromKnolyx($course) {

        $curs = self::where('k_id', $course['id'])->first();

        $input = [
            'name' => $course['title'],
            'descriere' => $course['description'],
            'type' => 'knolyx',
            'k_id' => $course['id'],
            'k_level' => array_key_exists('level', $course) ? $course['level'] : NULL,
            'k_duration' => array_key_exists('duration', $course) ? $course['duration'] : NULL,
            'k_number_students_enrolled' => array_key_exists('numberOfStudentsEnrolled', $course) ? $course['numberOfStudentsEnrolled'] : NULL,
            'k_from_training_tracker' => array_key_exists('fromTrainingTracker', $course) ? $course['fromTrainingTracker'] : NULL,
            'k_avatar' => array_key_exists('avatar', $course) ? $course['avatar'] : NULL,
            'created_by' => \Auth::user()->id,
            'updated_by' => \Auth::user()->id,
            'deleted' => 0,
        ];
		
        if(! $curs )
        {
			$curs = self::create($input);
        }
        else
        {
            $curs->update($input);
        }
    }



    // public static function CountLivrabile($customer_id) {

    //     return $customer_id;
    // } 


    public static function doInsert($input, $curs) {

        if($input['file'] && ($input['file'] instanceof UploadedFile))
        {
            $input['file'] = self::saveFile($input['file']);
        }

        $curs = self::create($input);

        $curs->SyncInfos();

        return $curs;
    }

    public static function doUpdate($input, $curs) {
        
        if($input['file'] && ($input['file'] instanceof UploadedFile))
        {
            $input['file'] = self::saveFile($input['file']);
        }

        $curs->update($input);
        $curs->SyncInfos();

        return $curs;
    }

    public static function doDelete($input, $curs) {
        $curs->deleted = 1;
        $curs->deleted_by = \Auth::user()->id;

        if(! $curs->props )
        {
            $curs->props = [];
        }

        $curs->props = [
            ...$curs->props,
            'k_id' => $curs->k_id,
            'name' => $curs->name,
        ];

        $curs->k_id = NULL;
        $curs->name = '#' . $curs->id . '#' . $curs->name;

        $curs->save();
        return $curs;
    }

    public static function saveFile($file) {
        $ext = strtolower($file->extension());

        if(in_array($ext, ['pdf']))
        {
            $filename = md5(time()) . '-' . \Str::slug(str_replace($file->extension(), '', $file->getClientOriginalName())) . '.' .  strtolower($file->extension());
            
            $result = $file->storeAs('cursuri/' .  \Auth::user()->id, $filename, 's3');

            $inputdata = [
                'file_original_name' => $file->getClientOriginalName(),
                'file_original_extension' => $file->extension(),
                'file_full_name' => $filename,
                'file_mime_type' => $file->getMimeType(),
                'file_upload_ip' => request()->ip(),
                'file_size' => $file->getSize(),
                'url' => config('filesystems.disks.s3.url') . $result,
                'created_by' => \Auth::user()->id,
            ];
            
            return $inputdata;
        }
        else
        {
            throw new \Exception('Fișier incorect.');
        }
    }

    public static function GetRules($action, $input) {

        if(! in_array($action, ['insert', 'update']) )
        {
            return NULL;
        }

        $result = [
            'name' => 'required|unique:cursuri,name',
            'category_id' => 'required|exists:categories,id',
            'type' => 'required|exists:cursuri-types,slug',
            'adresare_id' => 'required',
            'tip' => 'in:online,onsite'
        ];

        if($input['type'] == 'fisier')
        {
            if( ($action == 'insert') || (($action == 'update')  && (! $input['file'] )) )
            {
                $result['file'] = [
                    new IsFilePresent($action, $input),
                    'file',
                    'max:5242880',
                    'mimes:pdf',
                    'mimetypes:application/pdf',
                ];
            }
        }

        if( ($input['type'] == 'link') || ($input['type'] == 'youtube'))
        {
            $result['url'] = [
                new IsUrlPresent($action, $input),
                'active_url'
            ];
        }

        if($action == 'update')
        {
            $result['name'] .= (',' . $input['id']);
        }

        return $result;
    }

    public static function GetMessages($action, $input) {

        return [
            'name.required' => 'Denumirea cursului trebuie completată.',
            'category_id.required' => 'Categoria trebuie selectată.',
            'type.required' => 'Tipul cursului trebuie selectat.',
            'url.url' => 'Linkul nu pare sa fie valid.',
        ];
    }

    public static function getQuery() {
        return 
            self::query()
            ->leftJoin(
                'categories',
                function($j) {
                    $j->on('categories.id', '=', 'cursuri.category_id');
                }
            )
            ->leftJoin(
                'cursuri-adresare',
                function($j) {
                    $j->on('cursuri-adresare.id', '=', 'cursuri.adresare_id');
                }
            )
            ->select('cursuri.*')
        ;
    }

    /**
     * Actualizeaza/sincronizeaza unele campuri
     * tematica_count = numarul de tematici
     * curs_status = varianta human pentru status
     */
    public static function CalculateInfos() {
        foreach(self::all() as $i => $curs)
        {
            $curs->SyncInfos();
        }
    }

    public function SyncInfos() {
        $this->tematica_count = (!! $this->tematica ? count($this->tematica) : 0);
        $this->curs_status = self::$statuses[$this->status];
        $this->save();
    }

}