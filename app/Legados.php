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
class Legados extends Model implements TranslatableContract
{
    use SoftDeletes;
    use UploadableTrait;
    use AuditableTrait;
    use Translatable;


    public $table = 'legados';

    /**
     * Translatable
     */

    public $translatedAttributes = ['titulo','cuerpo','boton_titulo','boton_url'];

    /**
     * Uploadable
     *
     * files, targetDir, tmpDir, disk
     */

    public $files = ['foto'];
    public $targetDir = 'legados';




    protected $dates = ['deleted_at'];


    public $fillable = [
        'foto',
        'orden',
        'enabled'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'titulo' => 'string',
        'cuerpo' => 'string',
        'foto' => 'string',
        'boton_titulo' => 'string',
        'boton_url' => 'string',
        'orden' => 'integer',
        'enabled' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'titulo' => 'required',
        'cuerpo' => 'required',
        'enabled' => 'boolean'
    ];

    /**
     * Appends Attributes
     *
     * @var array
     */
    protected $appends = ['foto_url'];

    public function getFotoUrlAttribute($value)
    {
        return \FUHelper::fullUrl($this->targetDir,$this->foto);
    }




    protected static function boot()
    {
        parent::boot();

        static::deleted(function ($model)
        {
            $model->deleteTranslations();
        });
    }

}
