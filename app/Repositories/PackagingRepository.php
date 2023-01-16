<?php

namespace App\Repositories;

use App\Packaging;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class PackagingRepository
 * @package App\Repositories
 * @version November 28, 2022, 8:00 am -03
 *
 * @method Packaging findWithoutFail($id, $columns = ['*'])
 * @method Packaging find($id, $columns = ['*'])
 * @method Packaging first($columns = ['*'])
*/
class PackagingRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Packaging::class;
    }
}
