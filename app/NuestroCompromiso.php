<?php

namespace App;

use App\Traits\UploadableTrait;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Yajra\Auditable\AuditableTrait;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

/**
 * Class Legados
 * @package App
 * @version October 24, 2022, 6:36 pm -03
 *
 * @property string titulo
 * @property string cuerpo
 * @property string foto
 * @property string boton_titulo
 * @property string boton_url
 * @property integer orden
 * @property boolean visible
 */
class NuestroCompromiso extends Model
{
    //use SoftDeletes;
    use UploadableTrait;
    //use AuditableTrait;
    //use Translatable;


    public $table = 'nuestro_compromiso';



    /**
     * Uploadable
     *
     * files, targetDir, tmpDir, disk
     */

    public $files = ['imagen_home','imagen_interna'];
    public $targetDir = 'nuestro_compromiso';




    protected $dates = ['deleted_at'];


    public $fillable = [
        'imagen_home',
        'imagen_interna',
        'titulo',
        'codigo'
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
        'titulo' => 'required',
        'imagen_home' => 'required'
    ];

    /**
     * Appends Attributes
     *
     * @var array
     */
    protected $appends = ['imagen_home_url','imagen_interna_url'];

    public function getImagenHomeUrlAttribute($value)
    {
        return \FUHelper::fullUrl($this->targetDir,$this->imagen_home);
    }
    public function getImagenInternaUrlAttribute($value)
    {
        return $this->imagen_interna ? \FUHelper::fullUrl($this->targetDir,$this->imagen_interna) : null;
    }



    protected static function boot()
    {
        parent::boot();

        static::deleted(function ($model)
        {
            //$model->deleteTranslations();
        });
    }

}
