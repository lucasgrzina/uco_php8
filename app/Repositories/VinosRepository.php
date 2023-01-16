<?php

namespace App\Repositories;

use App\Vino;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class VinosRepository
 * @package App\Repositories
 * @version November 10, 2022, 11:13 am -03
 *
 * @method Vinos findWithoutFail($id, $columns = ['*'])
 * @method Vinos find($id, $columns = ['*'])
 * @method Vinos first($columns = ['*'])
*/
class VinosRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'titulo' => 'like'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Vino::class;
    }

    public function porColeccion($coleccion = 'FT') {
        return $this->model->newQuery()
                ->with(['aniadas' => function($q) {
                    $q->orderBy('anio','desc');
                }])
                ->whereEnabled(true)
                ->whereColeccion($coleccion)
                ->orderBy('orden')
                ->get();
    }
}
