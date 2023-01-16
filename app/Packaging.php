<?php

namespace App;

use App\Traits\UploadableTrait;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Yajra\Auditable\AuditableTrait;
//use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

/**
 * Class Packaging
 * @package App
 * @version November 28, 2022, 8:00 am -03
 *
 * @property integer unidades
 * @property decimal alto
 * @property decimal largo
 * @property decimal ancho
 * @property integer peso
 */
class Packaging extends Model
{
    use SoftDeletes;

    use AuditableTrait;
    // use Translatable;
    //use UploadableTrait;

    public $table = 'packagings';

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
    //public $targetDir = 'packagings';




    protected $dates = ['deleted_at'];


    public $fillable = [
        'unidades',
        'alto',
        'largo',
        'ancho',
        'peso',
        //'enabled'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'unidades' => 'integer',
        'peso' => 'float(5,2)',
        'alto' => 'float(5,2)',
        'largo' => 'float(5,2)',
        'ancho' => 'float(5,2)',
        'enabled' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'unidades' => 'required|unique:packagings,unidades,{:id},id',
        'alto' => 'required',
        'largo' => 'required',
        'ancho' => 'required',
        'peso' => 'required',
        //'enabled' => 'boolean'
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
