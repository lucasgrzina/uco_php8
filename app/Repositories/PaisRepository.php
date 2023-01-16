<?php

namespace App\Repositories;

use App\Pais;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class PaisRepository
 * @package App\Repositories
 * @version November 29, 2022, 7:10 pm -03
 *
 * @method Pais findWithoutFail($id, $columns = ['*'])
 * @method Pais find($id, $columns = ['*'])
 * @method Pais first($columns = ['*'])
*/
class PaisRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'codigo',
        'nombre'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Pais::class;
    }
}
