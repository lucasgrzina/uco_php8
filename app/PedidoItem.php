<?php

namespace App;

//use App\Traits\UploadableTrait;
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
class PedidoItem extends Model
{
    use SoftDeletes;

    public $table = 'pedido_items';

    protected $dates = ['deleted_at'];

    public $fillable = [
        'pedido_id',
        'aniada_id',
        'precio_pesos',
        'precio_usd',
        'cantidad',
        //'enabled'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'pedido_id' => 'integer',
        'aniada_id' => 'integer',
        'precio_pesos' => 'float(15,2)',
        'precio_usd' => 'float(15,2)',
        'cantidad' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
    ];

    public function pedido()
    {
        return $this->belongsTo('App\Pedidos', 'pedido_id');
    }

    public function aniada()
    {
        return $this->belongsTo('App\Aniada', 'aniada_id');
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
