<?php

namespace App\Repositories;

use App\Contactos;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class ContactosRepository
 * @package App\Repositories
 * @version November 4, 2022, 10:15 am -03
 *
 * @method Contactos findWithoutFail($id, $columns = ['*'])
 * @method Contactos find($id, $columns = ['*'])
 * @method Contactos first($columns = ['*'])
*/
class ContactosRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nombre' => 'like',
        'apellido' => 'like',
        'email' => 'like',
        'pais' => 'like',
        'tel_numero' => 'like',
        'mensaje' => 'like',
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Contactos::class;
    }
}
