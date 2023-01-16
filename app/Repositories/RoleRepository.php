<?php

namespace App\Repositories;


use InfyOm\Generator\Common\BaseRepository;
use App\Role;

/**
 * Class RoleRepository
 * @package App\Repositories
 * @version November 13, 2017, 5:53 pm UTC
 *
 * @method Role findWithoutFail($id, $columns = ['*'])
 * @method Role find($id, $columns = ['*'])
 * @method Role first($columns = ['*'])
*/
class RoleRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name' => 'like'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Role::class;
    }

    public function getActivos($columnas=['*'])
    {
        return Role::whereGuardName('admin')->whereEnabled(true)->select($columnas)->orderBy('name')->get();
    }
}
