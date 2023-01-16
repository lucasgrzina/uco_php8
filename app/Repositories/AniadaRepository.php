<?php

namespace App\Repositories;

use App\Aniada;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class AniadaRepository
 * @package App\Repositories
 * @version November 10, 2022, 6:18 pm -03
 *
 * @method Aniada findWithoutFail($id, $columns = ['*'])
 * @method Aniada find($id, $columns = ['*'])
 * @method Aniada first($columns = ['*'])
*/
class AniadaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'anio',
        'sku'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Aniada::class;
    }
}
