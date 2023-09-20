<?php

namespace App;


use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Yajra\Auditable\AuditableTrait;
//use Dimsav\Translatable\Translatable;


class Configuraciones extends Model
{
    //use SoftDeletes;

    //use AuditableTrait;
    //use Translatable;
    //use UploadableTrait;

    public $table = 'configuraciones';

    /**
     * Translatable
     */

    //public $translatedAttributes = ['name'];

    /**
     * Uploadable
     *
     * files, targetDir, tmpDir, disk
     */

    //public $files = ['the_file'];
    //public $targetDir = 'alertas';




    //protected $dates = ['deleted_at'];


    public $fillable = [
        'clave',
        'valor'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [

    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'valor' => 'required',
        'clave' => 'required',

    ];

    /**
     * Appends Attributes
     *
     * @var array
     */
    //protected $appends = ['the_file_url'];

    /*public function getTheFileUrlAttribute($value)
    {
        return \FUHelper::fullUrl($this->targetDir,$this->the_file);
    }*/



    protected static function boot()
    {
        parent::boot();

        /*static::deleted(function ($model)
        {
            $model->deleteTranslations();
            $model->name = $model->id . '_' . $model->name;
            $model->save();
        });*/
    }

}
