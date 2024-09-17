<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HomeSliderTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['titulo','subtitulo','boton_titulo','boton_url',"titulo_align_desktop","titulo_align_mobile","subtitulo_align_desktop","subtitulo_align_mobile"];
}
