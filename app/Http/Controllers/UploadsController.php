<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\StorageHelper;
use App\Helpers\FileUploadHelper;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\Traits\FileUploadTrait;

class UploadsController extends AppBaseController
{
    use FileUploadTrait;

    public function storeFile(Request $request)
    {
    	$file = $this->saveFile($request,'file','tmp');
    	return $this->sendResponse([
    		'file' => $file,
    		'path' => FileUploadHelper::fullUrl('tmp',$file)
    	], trans('api.success'));
    }

    public function storeImage(Request $request)
    {
    	$file = $this->saveImage($request,'file','tmp');
    	return $this->sendResponse(['file' => $file], trans('api.success'));
    }   
    
    public function subirArchivo(Request $request)
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);
        try {
            $disk = 'uploads';
            $inputName = 'file';
            $path = 'tmp';
            $pathSubida = '';
            $uploadPath = \FUHelper::path($path, $disk);
            
            if (!StorageHelper::existe($uploadPath)) {
                StorageHelper::crearDirectorio($uploadPath);
            }

            if ($request->hasFile($inputName)) {
                $ext = $request->file($inputName)->getClientOriginalExtension();
                $filename = time() . '-' . str_replace(' ', '-', $request->file($inputName)->getClientOriginalName());

                // Permito estas extensiones
                if (in_array(strtolower($ext), ['gif', 'jpeg', 'png', 'jpg', 'jpeg', 'bmp'])) {
                    /*$img = Image::make($_FILES['file']['tmp_name']);

                    if ($img->width() > 1200) {
                        //\Log::info('w > 1200');
                        $img->resize(null, 1200, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                    }


                    try {
                        $img->orientate();
                    } catch (\Exception $e) {
                    }

                    $pathSubida = $path . '/' . $filename;

                    StorageHelper::put(
                        $path . '/' . $filename,
                        $img->stream()
                    );

                    unset($img);

                    $path = $path . '/' . $filename;*/
                    $path = $request->file($inputName)->storeAs($path, $filename, $disk);
                } else {
                    $path = $request->file($inputName)->storeAs($path, $filename, $disk);
                }
            } else {
                $filename = $request->get($inputName, '');
            }

            return [
                'file' => $filename,
                'path' => StorageHelper::url($path),
                'visibility' => Storage::disk($disk)->getVisibility($path),
                'path_subida' => $pathSubida
            ];
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 500);            
            //return $this->response->error($e->getMessage(), 500);
        }
    }    
    
}
