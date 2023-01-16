<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LegadosTranslation extends Model
{
    public $timestamps = false;
    public $table = 'legado_translations';
    protected $fillable = ['titulo','cuerpo','boton_titulo','boton_url'];
}