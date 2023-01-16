<?php
namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait UploadableTrait
{
    protected function getArrayableAppends()
    {
        $this->appends = array_unique(array_merge($this->appends, ['uploads_url']));

        return parent::getArrayableAppends();
    }

    public function getUploadsUrlAttribute($value) 
    {
        $targetDir = $this->targetDir ? $this->targetDir : '/';
        return \FUHelper::fullUrl($targetDir);
    }

    public static function bootUploadableTrait()
    {

        static::updating(function ($model)
        {
            $disk = $model->disk ? $model->disk : 'uploads';
            $tmpDir = $model->tmpDir ? $model->tmpDir : 'tmp';
            $targetDir = $model->targetDir ? $model->targetDir : '/';

            $dirty = $model->getDirty();     

            foreach ($dirty as $key => $value) 
            {
                if (in_array($key, $model->files))
                {
                    //El campo q cambio es un file
                    $actual = $model->getOriginal($key);
                    $new =  $value;

                    
                    //Si llega algo nuevo, tengo que fijarme si tengo algo anterior y borrarlo y pasar lo nuevo
                    if ($actual && $actual != $new)
                    {
                        //Existe algo actualemnte y es distinto a lo nuevo. Borro lo actual
                        $model->removeFileFromDisk($actual);
                    }

                    if ($new)
                    {
                        $model->moveFileFromTempDir($new);    
                    }
                }
            }

            return true;
        });      

        static::creating(function ($model)
        {

            $disk = $model->disk ? $model->disk : 'uploads';
            $tmpDir = $model->tmpDir ? $model->tmpDir : 'tmp';
            $targetDir = $model->targetDir ? $model->targetDir : '/';


            $dirty = $model->getDirty();     

            foreach ($dirty as $key => $value) 
            {
                if (in_array($key, $model->files))
                {
                    //El campo q cambio es un file
                    $actual = $model->getOriginal($key);
                    $new =  $value;

                    
                    //Si llega algo nuevo, tengo que fijarme si tengo algo anterior y borrarlo y pasar lo nuevo
                    if ($actual && $actual != $new)
                    {
                        //Existe algo actualemnte y es distinto a lo nuevo. Borro lo actual
                        $model->removeFileFromDisk($actual);
                    }

                    if ($new)
                    {
                        $model->moveFileFromTempDir($new);    
                    }
                }
            }


            return true;
        });

        static::deleted(function ($model)
        {
            foreach($model->files as $file)
            {
                //\Log::info("10");
                $model->removeFileFromDisk($model->$file);
            }
            return true;
        });   
    }    

    public function removeFileFromDisk($file)
    {
        $disk = $this->disk ? $this->disk : 'uploads';
        $tmpDir = $this->tmpDir ? $this->tmpDir : 'tmp';
        $targetDir = $this->targetDir ? $this->targetDir : '/';    

        if ($file && Storage::disk($disk)->exists($targetDir.'/'.$file))
        {
            Storage::disk($disk)->delete($targetDir.'/'.$file);    
        }            
    }

    public function moveFileFromTempDir($file)
    {
        $disk = $this->disk ? $this->disk : 'uploads';
        $tmpDir = $this->tmpDir ? $this->tmpDir : 'tmp';
        $targetDir = $this->targetDir ? $this->targetDir : '/';            

        if (Storage::disk($disk)->exists($tmpDir.'/'.$file))
        {
            Storage::disk($disk)->move($tmpDir.'/'.$file,$targetDir.'/'.$file);
        }
    }
}