<?php

namespace App;

use Carbon\Carbon;
use Eloquent as Model;
use App\Traits\UploadableTrait;

use Yajra\Auditable\AuditableTrait;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Nota
 * @package App
 * @version October 25, 2022, 6:18 pm -03
 *
 * @property string titulo
 * @property string cuerpo
 * @property string foto
 * @property date fecha
 * @property char visible_home
 * @property integer orden
 */
class Nota extends Model implements TranslatableContract
{
    use SoftDeletes;

    use AuditableTrait;
    use Translatable;
    use UploadableTrait;

    public $table = 'notas';

    /**
     * Translatable
     */

    public $translatedAttributes = ['titulo','cuerpo','bajada'];

    /**
     * Uploadable
     *
     * files, targetDir, tmpDir, disk
     */

    public $files = ['foto'];
    public $targetDir = 'notas';




    protected $dates = ['deleted_at'];


    public $fillable = [
        //'titulo',
        //'cuerpo',
        'foto',
        'fecha',
        'visible_home',
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
        'fecha' => 'date:Y-m-d',
        'visible_home' => 'string',
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
        'fecha' => 'required',
        'visible_home' => 'required',
        'enabled' => 'boolean'
    ];

    /**
     * Appends Attributes
     *
     * @var array
     */
    protected $appends = ['foto_url','fecha_corta'];

    public function getFotoUrlAttribute($value)
    {
        return \FUHelper::fullUrl($this->targetDir,$this->foto);
    }

    public function getFechaCortaAttribute($value)
    {
        return strtoupper(Carbon::parse($this->attributes['fecha'])->format('d.M'));
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
