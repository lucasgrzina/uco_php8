<?php

namespace App\Repositories;

use App\Legados;
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
class LegadosRepository extends BaseRepository
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
        return Legados::class;
    }

    public function activos() {
        return $this->model->newQuery()->whereEnabled(true)->orderBy('orden')->get();
    }
}
