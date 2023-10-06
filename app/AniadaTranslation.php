<?php

namespace App;

use App\Traits\UploadableTrait;
use Illuminate\Database\Eloquent\Model;

class AniadaTranslation extends Model
{
	use UploadableTrait;

    public $files = ['ficha'];
    public $targetDir = 'aniadas';

    public $timestamps = false;

    protected $fillable = ['ficha','descripcion'];

    protected $appends = ['ficha_url'];

    public function getFichaUrlAttribute($value)
    {
        return isset($this->attributes['ficha']) ?  \FUHelper::fullUrl($this->targetDir,$this->attributes['ficha']) : null ;
    }
}
