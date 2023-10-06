<?php

namespace App;

use App\Traits\UploadableTrait;
use Illuminate\Database\Eloquent\Model;

class VinoTranslation extends Model
{
    use UploadableTrait;

    public $files = ['imagen'];
    public $targetDir = 'vinos';
    public $timestamps = false;
    protected $fillable = ['titulo','descripcion','imagen'];
    protected $appends = ['imagen_url'];

    public function getImagenUrlAttribute($value)
    {
        return \FUHelper::fullUrl($this->targetDir,$this->imagen);
    }
}
