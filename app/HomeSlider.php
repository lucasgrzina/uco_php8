<?php

namespace App;

use App\Traits\UploadableTrait;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Yajra\Auditable\AuditableTrait;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
/**
 * Class HomeSlider
 * @package App
 * @version October 27, 2022, 5:24 pm -03
 *
 * @property string imagen_mobile
 * @property string imagen_desktop
 * @property string video
 * @property string titulo
 * @property string subtitulo
 * @property string boton_titulo
 * @property string boton_url
 * @property integer orden
 */
class HomeSlider extends Model implements TranslatableContract
{
    use SoftDeletes;

    use AuditableTrait;
    use Translatable;
    use UploadableTrait;

    public $table = 'home_sliders';

    /**
     * Translatable
     */

    public $translatedAttributes  = ['titulo','subtitulo','boton_titulo','boton_url'];

    /**
     * Uploadable
     *
     * files, targetDir, tmpDir, disk
     */

    public $files = ['imagen_mobile','imagen_desktop','video'];
    public $targetDir = 'home_sliders';




    protected $dates = ['deleted_at'];


    public $fillable = [
        'imagen_mobile',
        'imagen_desktop',
        'video',
        'titulo',
        'subtitulo',
        'boton_titulo',
        'boton_url',
        'orden',
        'enabled'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'imagen_mobile' => 'string',
        'imagen_desktop' => 'string',
        'video' => 'string',
        'titulo' => 'string',
        'subtitulo' => 'string',
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
        //'imagen_mobile' => '',
        'imagen_desktop' => 'required_without:video',
        'video' => 'required_without:imagen_desktop',
        'enabled' => 'boolean'
    ];

    /**
     * Appends Attributes
     *
     * @var array
     */
    protected $appends = ['imagen_mobile_url','imagen_desktop_url','video_url'];


    public function getImagenMobileUrlAttribute($value)
    {
        return \FUHelper::fullUrl($this->targetDir,$this->imagen_mobile);
    }

    public function getImagenDesktopUrlAttribute($value)
    {
        return \FUHelper::fullUrl($this->targetDir,$this->imagen_desktop);
    }

    public function getVideoUrlAttribute($value)
    {
        return \FUHelper::fullUrl($this->targetDir,$this->video);
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
