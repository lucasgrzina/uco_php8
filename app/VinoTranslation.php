<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VinoTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['titulo','descripcion'];
}