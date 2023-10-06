<?php

namespace App;

use App\Vino;
use Eloquent as Model;
use App\Traits\UploadableTrait;

use Yajra\Auditable\AuditableTrait;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Aniada
 * @package App
 * @version November 10, 2022, 6:18 pm -03
 *
 * @property integer anio
 * @property string ficha
 * @property integer vino_id
 * @property integer stock
 * @property decimal precio_pesos
 * @property decimal precio_usd
 * @property string sku
 */
class Aniada extends Model implements TranslatableContract
{
    //use SoftDeletes;

    use AuditableTrait;
     use Translatable;
    //use UploadableTrait;

    public $table = 'aniadas';

    /**
     * Translatable
     */

    public $translatedAttributes = ['ficha','descripcion'];

    /**
     * Uploadable
     *
     * files, targetDir, tmpDir, disk
     */

    //public $files = ['the_file'];
    public $targetDir = 'aniadas';




    protected $dates = ['deleted_at'];


    public $fillable = [
        'anio',
        'ficha',
        'vino_id',
        'descripcion',
        'stock',
        'precio_pesos',
        'precio_usd',
        'sku',
        'enabled'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'anio' => 'integer',
        'ficha' => 'string',
        'vino_id' => 'integer',
        'stock' => 'integer',
        'sku' => 'string',
        'precio_pesos' => 'float:15,2',
        'precio_usd' => 'float:15,2',
        'enabled' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'anio' => 'required',
        'vino_id' => 'required',
        'sku' => 'required',
        //'enabled' => 'boolean'
    ];

    /**
     * Appends Attributes
     *
     * @var array
     */
    protected $appends = ['ficha_url'];

    public function getFichaUrlAttribute($value)
    {
        return ($this->ficha ?  \FUHelper::fullUrl($this->targetDir,$this->ficha) : null) ;
    }

    public function vino()
    {
        return $this->belongsTo(Vino::class, 'vino_id');
    }


    protected static function boot()
    {
        parent::boot();

        static::deleted(function ($model)
        {
            $model->deleteTranslations();
            //$model->name = $model->id . '_' . $model->name;
            //$model->save();
        });
    }

}
