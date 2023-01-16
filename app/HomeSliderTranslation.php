<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HomeSliderTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['titulo','subtitulo','boton_titulo','boton_url'];
}