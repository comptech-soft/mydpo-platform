<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;

use MyDpo\Performers\Upload\GetFileProperties;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rule;

class Upload extends Model {
    
    protected $table = 'uploads';

    protected $casts = [
        'id' => 'integer',
        
        'user_id' => 'integer',
        'size' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        
        'request' => 'json',
        'props' => 'json',
    ];

    protected $fillable = [
        'id',

        'user_id',
        'original_name',
        'extension',
        'name',
        'path',
        'mime_type',
        'upload_ip',
        'url',
        'size',
        'width',
        'height',
        'request',
        'props',

        'created_by',
        'updated_by',
    ];


    public static function CreateOrUpdate($record) {
        $upload = self::where('user_id', $record['user_id'])->where('name', $record['name'])->first();
        
        if( ! $upload )
        {
            $upload = self::create($record);
        }
        
        $upload->update($record);

        return $upload;
    }

    public static function getFileProperties($input) {
        return 
            (new GetFileProperties(
                $input, 
                [
                    'file' => self::MakeRulesFromInput($input),
                ], 
                []
            ))
            ->SetSuccessMessage('OKKK...')
            ->SetExceptionMessage([
                \Exception::class => NULL,
            ])
            ->Perform();
    }

    public static function MakeRulesFromInput($input) {

        $fileRule = File::default();
        $inputRules = $input['rules'];

        if($input['is_image'] == 1)
        {
            $fileRule = $fileRule->image();

            if(array_key_exists('maxsize', $inputRules))
            {
                $fileRule = $fileRule->max($inputRules['maxsize']);
            }

            if(array_key_exists('minsize', $inputRules))
            {
                $fileRule = $fileRule->min($inputRules['minsize']);
            }

            if(array_key_exists('dimensions', $inputRules))
            {
                $dimensions = $inputRules['dimensions'];
                
                $ruleDimensions = Rule::dimensions();

                if(array_key_exists('minwidth', $dimensions))
                {
                    $ruleDimensions = $ruleDimensions->minWidth($dimensions['minwidth']);
                }

                if(array_key_exists('maxwidth', $dimensions))
                {
                    $ruleDimensions = $ruleDimensions->maxWidth($dimensions['maxwidth']);
                }

                if(array_key_exists('minheight', $dimensions))
                {
                    $ruleDimensions = $ruleDimensions->minHeight($dimensions['minheight']);
                }

                if(array_key_exists('maxheight', $dimensions))
                {
                    $ruleDimensions = $ruleDimensions->maxHeight($dimensions['maxheight']);
                }


                $fileRule = $fileRule->dimensions($ruleDimensions);
            }

        }

    
        $rules = [
            'required',
            $fileRule
        ];


        return $rules;
    }

    

}
