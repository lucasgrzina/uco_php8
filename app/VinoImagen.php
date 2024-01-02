<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UploadableTrait;
class VinoImagen extends Model
{
    //
    use UploadableTrait;
    public $table = 'vino_imagenes';

    public $files = ['filename'];
    public $targetDir = 'videos/imagenes';

    public $fillable = [
        'filename',
        'video_id',
        'orden'
    ];    

    protected $casts = [
        'filename' => 'string',
        'video_id' => 'integer',
        'orden' => 'integer',
    ];

    public static $rules = [
        'filename' => 'required',
        'orden' => 'required'
    ];    

    protected $appends = ['filename_url','delete'];

    public function getFilenameUrlAttribute($value) 
    {
        return ($this->attributes['filename'] ?  \FUHelper::fullUrl($this->targetDir,$this->attributes['filename']) : null) ;
    }

    public function getDeleteAttribute($value) 
    {
        return false;
    }       


}