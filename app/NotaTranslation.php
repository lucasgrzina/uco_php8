<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotaTranslation extends Model
{
    public $timestamps = false;
    
    protected $fillable = ['titulo','cuerpo','bajada'];
}