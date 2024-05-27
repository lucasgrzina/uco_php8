<?php

namespace App\Repositories;

use App\NuestroCompromiso;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class LegadosRepository
 * @package App\Repositories
 * @version October 24, 2022, 6:36 pm -03
 *
 * @method Legados findWithoutFail($id, $columns = ['*'])
 * @method Legados find($id, $columns = ['*'])
 * @method Legados first($columns = ['*'])
*/
class NuestroCompromisoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'titulo' => 'like',
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return NuestroCompromiso::class;
    }

    public function getFotosInternas() {
        return $this->model->orderBy('id')->select('imagen_interna','codigo')->get();
    }

}
