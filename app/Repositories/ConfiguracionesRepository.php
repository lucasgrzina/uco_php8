<?php

namespace App\Repositories;

use App\Contactos;
use App\Configuraciones;
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
class ConfiguracionesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'clave',
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Configuraciones::class;
    }
}
