<?php

namespace App;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Yajra\Auditable\AuditableTrait;
//use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
//use Astrotomic\Translatable\Translatable;

/**
 * Class Pedido
 * @package App
 * @version November 22, 2022, 10:03 pm -03
 *
 * @property integer registrado_id
 * @property string nombre
 * @property string apellido
 * @property string email
 */
class RegistradoDireccion extends Model
{
    use SoftDeletes;

    use AuditableTrait;
    // use Translatable;
    //use UploadableTrait;

    public $table = 'registrado_direcciones';

    protected $dates = ['deleted_at'];


    public $fillable = [
        'registrado_id',
        'nombre',
        'apellido',
        'calle',
        'ciudad',
        'cp',
        'provincia',
        'pais_id',
        'principal',
        'departamento',
        'info_adicional'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'registrado_id' => 'integer',
        'pais_id' => 'integer',
        'principal' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'registrado_id' => 'required',
        'nombre' => 'required',
        'calle' => 'required',
        'ciudad' => 'required',
        'provincia' => 'required',
        'cp' => 'required',
        'pais_id' => 'required',

        //'enabled' => 'boolean'
    ];

    /**
     * Appends Attributes
     *
     * @var array
     */

    public function registrado()
    {
        return $this->belongsTo('App\Registrado', 'registrado_id');
    }

    public function pais()
    {
        return $this->belongsTo('App\Pais', 'pais_id');
    }


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
