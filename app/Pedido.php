<?php

namespace App;

use App\Traits\UploadableTrait;
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
class Pedido extends Model
{
    use SoftDeletes;

    use AuditableTrait;
    // use Translatable;
    //use UploadableTrait;

    public $table = 'pedidos';

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
    //public $targetDir = 'pedidos';




    protected $dates = ['deleted_at'];


    public $fillable = [
        'registrado_id',
        'nombre',
        'apellido',
        'email',
        'dni',
        'cuit',
        'razon_social',
        'telefono',
        'direccion',
        'ciudad',
        'cp',
        'provincia',
        'provincia_id',
        'pais_id',
        'envio_retiro',
        'tipo_factura',
        'estado_id',
        'total_carrito',
        'total_carrito_usd',
        'total_envio',
        'total_envio_usd',
        'total',
        'total_usd',
        'cotizacion_usd',
        'comentarios',
        'nombre_fc','apellido_fc','dni_fc','direccion_fc','ciudad_fc','cp_fc','provincia_fc','pais_id_fc',
        'ups_etiqueta',
        'ups_tracking_number',
        'ups_info',
        'sincronizo_sap',
        'sincronizo_pago',
        'error_sincronizacion_sap',
        'documento_sap',
        'tipo_tarjeta',
        'tarjeta',
        'tarjeta_exp',
        'numero_voucher',
        'pp_payment_type',
        'pp_preference_id',
        'pp_status',
        'pp_status_desc',
        'tarjeta_cuotas'
        //'enabled'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'registrado_id' => 'integer',
        'provincia_id' => 'integer',
        'estado_id' => 'integer',
        'nombre' => 'string',
        'apellido' => 'string',
        'email' => 'string',
        'total_carrito' => 'float(15,2)',
        'total_envio' => 'float(15,2)',
        'total' => 'float(15,2)',
        'total_carrito_usd' => 'float(15,2)',
        'total_envio_usd' => 'float(15,2)',
        'total_usd' => 'float(15,2)',
        'cotizacion_usd' => 'float(7,2)',
        'pais_id_fc' => 'integer',
        'ups_info' => 'json',
        'sincronizo_sap' => 'boolean',
        'error_sincronizacion_sap' => 'string',
        'documento_sap' => 'integer',
        'tarjeta_cuotas' => 'integer'
        //'enabled' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'registrado_id' => 'required',
        //'enabled' => 'boolean'
    ];

    /**
     * Appends Attributes
     *
     * @var array
     */
    protected $appends = ['estado'];

    public function getEstadoAttribute($value)
    {
        switch ($this->attributes['estado_id']) {
            case 1:
                return trans('front.pedidos.estados.aprobado');
                break;
            case -1:
                return trans('front.pedidos.estados.rechazado');
                break;
            default:
                return trans('front.pedidos.estados.pendiente');
                break;
        }
    }

    public function registrado()
    {
        return $this->belongsTo('App\Registrado', 'registrado_id');
    }

    /*public function provincia()
    {
        return $this->belongsTo('App\Provincia', 'provincia_id');
    }*/

    public function pais()
    {
        return $this->belongsTo('App\Pais', 'pais_id');
    }

    public function items()
    {
        return $this->hasMany('App\PedidoItem', 'pedido_id');
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
