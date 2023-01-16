<?php

namespace App\Repositories;

use App\Proyecto;
use App\User;
use Illuminate\Support\Facades\Auth;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class UserRepository
 * @package App\Repositories
 * @version November 10, 2017, 8:03 pm UTC
 *
 * @method User findWithoutFail($id, $columns = ['*'])
 * @method User find($id, $columns = ['*'])
 * @method User first($columns = ['*'])
*/
class UserRepository extends BaseRepository
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
        return User::class;
    }
          
}
