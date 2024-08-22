<?php

namespace App\Repositories;

use App\Newsletters;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class NewslettersRepository
 * @package App\Repositories
 * @version November 2, 2022, 1:49 pm -03
 *
 * @method Newsletters findWithoutFail($id, $columns = ['*'])
 * @method Newsletters find($id, $columns = ['*'])
 * @method Newsletters first($columns = ['*'])
*/
class NewslettersRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nombre' => 'like',
        'apellido' => 'like',
        'email' => 'like',
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Newsletters::class;
    }
}
