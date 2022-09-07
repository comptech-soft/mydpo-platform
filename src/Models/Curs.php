<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Helpers\Performers\Datatable\GetItems;
use MyDpo\Helpers\Performers\Datatable\DoAction;
use MyDpo\Models\Category;
use MyDpo\Traits\DaysDifference;
use MyDpo\Performers\Curs\OpenKnolyxCourse;
use MyDpo\Performers\Curs\GetKnolyxCourses;
use MyDpo\Models\Knolyx;

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
    ];

    /**
     * 
     * ATTRIBUTES
     * 
     */
    public function getMyUrlAttribute() {
        if( ($this-> type == 'knolyx') && $this->k_id)
        {
            return config('knolyx.url') . $this->k_id;
        }
        return $this->url;
    }

    public function category() {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // public function customercursuri() {
    //     return $this->hasMany(CustomerCurs::class, 'curs_id');
    // }

    // public function fisiere() {
    //     return $this->hasMany(CursFisier::class, 'curs_id');
    // }

    public static function getQuery() {
        return 
            self::query()
            ->leftJoin(
                'categories',
                function($j) {
                    $j->on('categories.id', '=', 'cursuri.category_id');
                }
            )
            ->select('cursuri.*')
        ;
    }

    public static function getItems($input) {
        return (new GetItems(
            $input, 
            self::getQuery()->with([
                // 'customercursuri.trimitere.detalii.customer', 
                // 'customercursuri.participanti'
            ]), 
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
            'image' => mb_convert_encoding($image, 'UTF-8', 'UTF-8'),
        ];
    }

    public static function getKnolyxCourses($input) {
        return (new GetKnolyxCourses($input))
            ->Perform();
    }

    public static function openKnolyxCourse($input) {
        return (new OpenKnolyxCourse($input))
            ->Perform();
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