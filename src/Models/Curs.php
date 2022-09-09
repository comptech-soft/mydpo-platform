<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use MyDpo\Helpers\Performers\Datatable\GetItems;
use MyDpo\Helpers\Performers\Datatable\DoAction;
use MyDpo\Models\Category;
use MyDpo\Traits\DaysDifference;
use MyDpo\Performers\Curs\OpenKnolyxCourse;
use MyDpo\Performers\Curs\GetKnolyxCourses;
use MyDpo\Models\Knolyx;
use MyDpo\Rules\Curs\IsUrlPresent;
use MyDpo\Rules\Curs\IsFilePresent;
use MyDpo\Scopes\CursScope;

class Curs extends Model {

    use DaysDifference;

    protected $table = 'cursuri';

    protected $with = ['category'];

    // protected $withCount = ['fisiere'];

    protected $casts = [
        'id' => 'integer',
        'props' => 'json',
        'category_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
        'deleted' => 'integer',
        'tematica' => 'json',
        'file' => 'json',
        'k_avatar' => 'json',
    ];

    protected $fillable = [
        'id',
        'category_id',
        'name',
        'type',
        'descriere',
        'tematica',
        'url',
        'date_from',
        'date_to',
        'props',
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

    protected $appends = [
        'days_difference',
        'my_url',
        'my_image',
    ];

    protected static function booted() {
        static::addGlobalScope( new CursScope );
    }

    /**
     * 
     * ATTRIBUTES
     * 
     */
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
     * 
     * RELATIONS
     * 
     */
    public function category() {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // public function customercursuri() {
    //     return $this->hasMany(CustomerCurs::class, 'curs_id');
    // }

    // public function fisiere() {
    //     return $this->hasMany(CursFisier::class, 'curs_id');
    // }

    public static function PrepareActionInput($action, $input) {
        if(! array_key_exists('tematica', $input) )
        {
            $input['tematica'] = NULL;
        }
        else
        {
            if(! is_array($input['tematica']) )
            {
                $input['tematica'] = NULL;
            }
            else
            {
                if( count($input['tematica']) == 0)
                {
                    $input['tematica'] = NULL;
                }
            }
        }
        return $input;
    }

    public static function doAction($action, $input) {
        return (new DoAction($action, $input, __CLASS__))->Perform();
    }

    public static function doUpdate($input, $curs) {
        
        if($input['file'] && ($input['file'] instanceof UploadedFile))
        {
            $input['file'] = self::saveFile($input['file']);
        }

        $curs->update($input);

        return $curs;
    }

    public static function doDelete($input, $curs) {
        $curs->deleted = true;
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
        $curs->name = '#' . $curs->id . '-' . $curs->name;

        $curs->save();
        return $curs;
    }

    public static function doInsert($input, $curs) {
        
        if($input['file'] && ($input['file'] instanceof UploadedFile))
        {
            $input['file'] = self::saveFile($input['file']);
        }

        $curs = self::create($input);

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

    public static function GetMessages($action, $input) {

        return [
            'name.required' => 'Denumirea cursului trebuie completată.',
            'category_id.required' => 'Categoria trebuie selectată.',
            'type.required' => 'Tipul cursului trebuie selectat.',
            'url.url' => 'Linkul nu pare sa fie valid.',
        ];
    }

    public static function GetRules($action, $input) {
        if($action == 'delete')
        {
            return NULL;
        }
        $result = [
            'name' => 'required|unique:cursuri,name',
            
            'type' => 'required',
            
            'category_id' => 'required|exists:categories,id',
        ];

        if($input['type'] == 'fisier')
        {
            if( ($action == 'insert') || (($action == 'update')  && (! $input['file'] )) )
            {
                $result['file'] = [
                    new IsFilePresent($input),
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
                new IsUrlPresent($input),
                'active_url'
            ];
        }

        if($action == 'update')
        {
            $result['name'] .= (',' . $input['id']);
        }

        return $result;
    }


    // public static function getQuery() {
    //     return 
    //         self::query()
    //         ->leftJoin(
    //             'categories',
    //             function($j) {
    //                 $j->on('categories.id', '=', 'cursuri.category_id');
    //             }
    //         )
    //         ->select('cursuri.*')
    //     ;
    // }

    public static function getItems($input) {
        return (new GetItems(
            $input, 
            self::query(), 
            __CLASS__
        ))
        ->Perform();
    }

    public static function getKnolyxCoursesImages() {

        $courses = Curs::whereNotNull('k_id')->get();

        foreach($courses as $i => $curs)
        {
            $curs->getKnolyxImage();
        }

    }

    public function getKnolyxImage() {
        $image = Knolyx::getCourseImage($this->k_id);

        if( ! $this->props )
        {
            $this->props = [];
        }

        $this->props = [
            ...$this->props,
            'image' => $image,
        ];

        $this->save();
    }

    public static function getKnolyxCourses($input) {
        return (new GetKnolyxCourses($input))->Perform();
    }

    public static function openKnolyxCourse($input) {
        return (new OpenKnolyxCourse($input))->Perform();
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
            'k_level' => $course['level'],
            'k_duration' => $course['duration'],
            'k_number_students_enrolled' => $course['numberOfStudentsEnrolled'],
            'k_from_training_tracker' => $course['fromTrainingTracker'],
            'k_avatar' => $course['avatar'],
            'created_by' => \Auth::user()->id,
            'updated_by' => \Auth::user()->id,
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

}