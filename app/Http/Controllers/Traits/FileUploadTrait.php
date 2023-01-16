<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

trait FileUploadTrait
{
    public static $config;
    public static $disk = 'uploads';

    /**
     * File upload trait used in controllers to upload files
     */

    public function saveFiles(Request $request,$inputName = 'image',$path='')
    {

        $uploadPath  = \FUHelper::path($path,self::$disk);
		$thumbPath = $uploadPath.'/thumb';
        
        if (! file_exists($uploadPath)) {
            mkdir($uploadPath, 0775);
            mkdir($thumbPath, 0775);
        }

        $finalRequest = $request;
        foreach ($request->all() as $key => $value) {
            if ($request->hasFile($key)) {

                if ($request->has($key . '_max_width') && $request->has($key . '_max_height')) {
                    // Check file width
                    $filename = time() . '-' . str_replace(' ','-',$request->file($key)->getClientOriginalName());
                    $file     = $request->file($key);
                    $image    = Image::make($file);
                    if (! file_exists($thumbPath)) {
                        mkdir($thumbPath, 0775, true);
                    }
                    
                    $width  = $image->width();
                    $height = $image->height();
                    
                    $thumbwidth = 300;
                    $thumbheight = 300;
                    if ($height > $width)
                    {
                        $ratio = $thumbheight / $height;
                        $newheight = $thumbheight;
                        $newwidth = $width * $ratio;
                    }
                    else
                    {
                        $ratio = $thumbwidth / $width;
                        $newwidth = $thumbwidth;
                        $newheight = $height * $ratio;
                    }
                    Image::make($file)->fit(intval($newwidth), intval($newheight))->save($thumbPath . '/' . $filename);
                    
                    
                    if ($width > $request->{$key . '_max_width'} && $height > $request->{$key . '_max_height'}) {
                        $image->resize($request->{$key . '_max_width'}, $request->{$key . '_max_height'});
                    } elseif ($width > $request->{$key . '_max_width'}) {
                        $image->resize($request->{$key . '_max_width'}, null, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                    } elseif ($height > $request->{$key . '_max_width'}) {
                        $image->resize(null, $request->{$key . '_max_height'}, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                    }
                    $image->save($uploadPath . '/' . $filename);
                    $finalRequest = new Request(array_merge($finalRequest->all(), [$key => $filename]));
                } else {
                    $filename = time() . '-' . str_replace(' ','-',$request->file($key)->getClientOriginalName());
                    $request->file($key)->move($uploadPath, $filename);
                    $finalRequest = new Request(array_merge($finalRequest->all(), [$key => $filename]));
                }
            }
        }

        return $finalRequest;
    }

    public function saveFile(Request $request,$inputName = 'file',$path='')
    {
        $uploadPath  = \FUHelper::path($path,self::$disk);
        
        if (! file_exists($uploadPath)) {
            mkdir($uploadPath, 0775);
        }

        $filename = $request->get($inputName,'');
        if ($request->hasFile($inputName)) {
            $filename = time() . '-' . str_replace(' ','-',$request->file($inputName)->getClientOriginalName());

            $request->file($inputName)->move($uploadPath, $filename);
            //$finalRequest = new Request(array_merge($finalRequest->all(), [$inputName => $filename]));
        }

        return $filename;

    }

    public function saveImage(Request $request,$inputName = 'image',$path='')
    {
        $uploadPath  = \FUHelper::path($path,self::$disk);
        $thumbPath = $uploadPath.'/thumb';
        
        if (! file_exists($uploadPath)) {
            mkdir($uploadPath, 0775);
            mkdir($thumbPath, 0775);
        }

        $filename = $request->get($inputName,'');
        if ($request->hasFile($inputName)) {



            if ($request->has($inputName . '_max_width') && $request->has($inputName . '_max_height')) {
                // Check file width
                $filename = time() . '-' . str_replace(' ','-',$request->file($inputName)->getClientOriginalName());
                $file     = $request->file($inputName);
                $image    = Image::make($file);
                if (! file_exists($thumbPath)) {
                    mkdir($thumbPath, 0775, true);
                }
                
                $width  = $image->width();
                $height = $image->height();
                
                $thumbwidth = 300;
                $thumbheight = 300;
                if ($height > $width)
                {
                    $ratio = $thumbheight / $height;
                    $newheight = $thumbheight;
                    $newwidth = $width * $ratio;
                }
                else
                {
                    $ratio = $thumbwidth / $width;
                    $newwidth = $thumbwidth;
                    $newheight = $height * $ratio;
                }
                Image::make($file)->fit(intval($newwidth), intval($newheight))->save($thumbPath . '/' . $filename);
                
                
                if ($width > $request->{$inputName . '_max_width'} && $height > $request->{$inputName . '_max_height'}) {
                    $image->resize($request->{$inputName . '_max_width'}, $request->{$inputName . '_max_height'});
                } elseif ($width > $request->{$inputName . '_max_width'}) {
                    $image->resize($request->{$inputName . '_max_width'}, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                } elseif ($height > $request->{$inputName . '_max_width'}) {
                    $image->resize(null, $request->{$inputName . '_max_height'}, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                }
                $image->save($uploadPath . '/' . $filename);
                
            } else {
                $filename = time() . '-' . str_replace(' ','-',$request->file($inputName)->getClientOriginalName());
                $request->file($inputName)->move($uploadPath, $filename);
                
            }
        }

        return $filename;

    }
}