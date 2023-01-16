<?php

namespace App;

use App\Traits\UploadableTrait;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Yajra\Auditable\AuditableTrait;
//use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

/**
 * Class Contactos
 * @package App
 * @version November 4, 2022, 10:15 am -03
 *
 * @property string nombre
 * @property string apellido
 * @property string email
 * @property string pais
 * @property integer pais_id
 * @property string tel_prefijo
 * @property string tel_numero
 * @property string mensaje
 * @property boolean recibir_info
 */
class Contactos extends Model
{
    use SoftDeletes;

    use AuditableTrait;
    // use Translatable;
    //use UploadableTrait;

    public $table = 'contactos';

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
    //public $targetDir = 'contactos';




    protected $dates = ['deleted_at'];


    public $fillable = [
        'nombre',
        'apellido',
        'email',
        'pais',
        'pais_id',
        'tel_prefijo',
        'tel_numero',
        'mensaje',
        'recibir_info',
        //'enabled'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'nombre' => 'string',
        'apellido' => 'string',
        'email' => 'string',
        'pais' => 'string',
        'pais_id' => 'integer',
        'tel_prefijo' => 'string',
        'tel_numero' => 'string',
        'mensaje' => 'string',
        'recibir_info' => 'boolean',
        //'enabled' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'nombre' => 'required',
        'apellido' => 'required',
        'email' => 'required|email|max:255',
        'mensaje' => 'required',
        'recibir_info' => 'boolean',
        'acepto' => 'required|accepted'
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
