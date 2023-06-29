<?php

namespace MyDpo\Performers\System\Upload;

use MyDpo\Helpers\Perform;
use MyDpo\Models\System\Upload;
use MyDpo\Models\User;

class GetFileProperties extends Perform {

    public function Action() {
               
        $user = (array_key_exists('user_id', $this->input) && $this->input['user_id'])
            ? User::find($this->input['user_id'])
            : \Auth::user();

        if( ! $user )
        {
            throw new \Exception('Eroare la upload fișier. User inexistent');
        }

        $file = $this->input['file'];

        if(! $file || ! $file->isValid() )
        {
            throw new \Exception('Eroare la upload fișier. Fișier inexistent sau invalid .');
        }

        $extension = strtolower($file->extension());
        $full_name = strtolower($file->getClientOriginalName());


        $result = $file->storeAs( ($path = $this->input['path'] . '/' . $user->id), $full_name, 's3');

        if( ! $result )
        {
            throw new \Exception('Eroare la upload fișier. Amazon S3 refuză fișierul.');
        }

        $width = $height = NULL;
        $mime_type = $file->getMimeType();
        $url = config('filesystems.disks.s3.url') . $result;

        if($this->input['is_image'])
        {
            try
            {
                $image = \Image::make($file);
                $width = $image->width();
                $height = $image->height();
            }
            catch(\Exception $e)
            {
                $width = $height = NULL;
            }

            if(! $width || ! $height )
            {
                try
                {
                    $imgProps = getimagesize($url);
                    $width = $imgProps[0];
                    $height = $imgProps[1];
                    if($imgProps['mime'] && ($mime_type != $imgProps['mime']))
                    {
                        $mime_type .= (', ' . $imgProps['mime']);
                    }
                }
                catch(\Exception $e)
                {
                    $width = $height = NULL;
                }
            }

            if(! $width || ! $height )
            {
                throw new \Exception('Eroare la upload fișier. Nu se pot determina dimensiunile fișierului imagine.');
            }
        }

        $record = [
            'user_id' => $user->id,
            'original_name' => $full_name,
            'extension' => $extension,
            'name' => $result,
            'path' => $path,
            'mime_type' => $mime_type,
            'upload_ip' => request()->ip(),
            'url' => $url,
            'size' => $file->getSize(),
            'width' => $width,
            'height' => $height,
            'request' => [
                'url' => request()->url(),
                'full_url' => request()->fullUrl(),
                'host' => request()->host(),
                'http_host' => request()->httpHost(),
                'scheme_http_host' => request()->schemeAndHttpHost(),
                'method' => request()->method(),
                'ip' => request()->ip(),
            ],
        ];

        $this->payload = Upload::CreateOrUpdate($record);

    }
} 