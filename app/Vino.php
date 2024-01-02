<?php

namespace App;

use App\Aniada;
use Eloquent as Model;
use App\Traits\UploadableTrait;

use Illuminate\Support\Facades\App;
use Yajra\Auditable\AuditableTrait;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

/**
 * Class Vinos
 * @package App
 * @version November 10, 2022, 11:13 am -03
 *
 * @property string titulo
 * @property string imagen
 * @property string descripcion
 * @property decimal peso
 * @property decimal largo
 * @property decimal ancho
 * @property decima alto
 */
class Vino extends Model implements TranslatableContract
{
    use SoftDeletes;

    use AuditableTrait;
     use Translatable;
    //use UploadableTrait;

    public $table = 'vinos';

    /**
     * Translatable
     */

    public $translatedAttributes = ['titulo','imagen'];

    /**
     * Uploadable
     *
     * files, targetDir, tmpDir, disk
     */

    //public $files = ['imagen'];
    //public $targetDir = 'vinos';




    protected $dates = ['deleted_at'];


    public $fillable = [
        'titulo',
        //'imagen',

        'peso',
        'largo',
        'ancho',
        'alto',
        'enabled',
        'coleccion',
        'orden',
        'vendible'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'titulo' => 'string',
        //'imagen' => 'string',
        'descripcion' => 'string',
        'peso' => 'float',
        'largo' => 'float',
        'ancho' => 'float',
        'alto' => 'float',
        'enabled' => 'boolean',
        'orden' => 'integer',
        'vendible' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'titulo' => 'required',
        //'enabled' => 'boolean'
    ];

    /**
     * Appends Attributes
     *
     * @var array
     */
    protected $appends = ['imagen_url'];

    public function getImagenUrlAttribute($value)
    {
        $lang = App::getLocale();
        try
        {
            $filename = $this->translate($lang)->imagen_url;
            return $filename;
        }
        catch(\Exception $e) {
            return null;
        }
        //return \FUHelper::fullUrl($this->targetDir,$this->imagen);
    }


    public function aniadas()
    {
        return $this->hasMany(Aniada::class, 'vino_id');
    }



    protected static function boot()
    {
        parent::boot();

        static::deleted(function ($model)
        {
            $model->deleteTranslations();
            //$model->name = $model->id . '_' . $model->name;
            $model->save();
        });
    }

    public function imagenes()
    {
        return $this->hasMany('App\VinoImagen','vino_id');
    } 
}
