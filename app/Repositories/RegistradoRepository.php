<?php

namespace App\Repositories;


use App\Registrado;
use App\RegistradoDireccion;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class RegisteredRepository
 * @package App\Repositories
 * @version November 10, 2017, 8:03 pm UTC
 *
 * @method Registered findWithoutFail($id, $columns = ['*'])
 * @method Registered find($id, $columns = ['*'])
 * @method Registered first($columns = ['*'])
*/
class RegistradoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'usuario' => 'like',
        'email' => 'like'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Registrado::class;
    }

    public function guardarDireccion($data) {
        $model = $this->model->find($data['registrado_id']);

        if ($data['principal']) {
            $model->direcciones()->update(['principal' => false]);
        }
        if ($data['id'] > 0) {
            $direccion = $model->direcciones()->whereId($data['id'])->update($data);
        } else {
            $direccion = $model->direcciones()->create($data);
        }

        return $direccion;
    }

    public function eliminarDireccion($id) {
        $direccion = RegistradoDireccion::find($id);
        logger($direccion);
        if ($direccion) {
            $direccion = $direccion->delete();
        }

        return true;
    }
}
