<?php

namespace MyDpo\Performers\System;

use MyDpo\Helpers\Perform;
use MyDpo\Models\SysConfig;
use MyDpo\Models\Platform;
use MyDpo\Models\MaterialStatus;
use MyDpo\Models\Language;
use MyDpo\Models\Translation;
use MyDpo\Models\Role;

class GetConfig extends Perform {

    public function Action() {
    
        $user = \Auth::user();

        $locale = app()->getLocale();

        if( ! in_array($locale, ['ro', 'en']) )
        {
            $locale = config('app.locale');
        }

        $this->payload = [
            'user' => $user ? $user : NULL,
            'locale' => $locale,
            'timezone' => config('app.timezone'),
            'name' => config('app.name'),
            'shortname' => config('app.shortname'),
            'url' => config('app.url'),
            'env' => config('app.env'),
            'platform' => config('app.platform'),
            'sysconfig' => SysConfig::all()->pluck('value', 'code'),
            'platforms' => Platform::all()->pluck('name', 'slug'),
            'material_statuses' => MaterialStatus::all(),
            'languages' => Language::getBySlug(),
            'roles' => Role::getBySlug(),
            'php' => [
                'post_max_size' => self::parseSize(ini_get('post_max_size')),
                'upload_max_filesize' => self::parseSize(ini_get('upload_max_filesize')),
                'upload_max_size' => self::fileUploadMaxSize(),
            ],
            'translations' => $locale == 'ro' ? [] : Translation::ToJavascriptVars($locale),
        ];

    }

    protected static function parseSize($size) {
        // Remove the non-unit characters from the size.
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size);
        
        // Remove the non-numeric characters from the size.
        $size = preg_replace('/[^0-9\.]/', '', $size); 
        if($unit) 
        {
            // Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
            return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
        }
        else 
        {
            return round($size);
        }
    }

    protected static function fileUploadMaxSize() {
        $max_size = -1;
      
        if ($max_size < 0) 
        {
            // Start with post_max_size.
            $post_max_size = self::parseSize(ini_get('post_max_size'));
            if ($post_max_size > 0) 
            {
                $max_size = $post_max_size;
            }
      
            // If upload_max_size is less, then reduce. Except if upload_max_size is
            // zero, which indicates no limit.
            $upload_max = self::parseSize(ini_get('upload_max_filesize'));
            if ($upload_max > 0 && $upload_max < $max_size) 
            {
                $max_size = $upload_max;
            }
        }
        return $max_size;
    }
} 