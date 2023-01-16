<?php

namespace App\Repositories;

use App\Nota;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class NotaRepository
 * @package App\Repositories
 * @version October 25, 2022, 6:18 pm -03
 *
 * @method Nota findWithoutFail($id, $columns = ['*'])
 * @method Nota find($id, $columns = ['*'])
 * @method Nota first($columns = ['*'])
*/
class NotaRepository extends BaseRepository
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
        return Nota::class;
    }

    public function destacadosHome() {
        return $this->model->newQuery()
                ->whereEnabled(true)
                ->where('visible_home','!=','NO')
                ->orderBy('visible_home')
                ->orderBy('orden')
                ->get()
                ->groupBy('visible_home')
                ->toArray();
    }

    public function recientes() {
        return $this->model->newQuery()
                ->whereEnabled(true)
                ->orderBy('fecha','desc')
                ->orderBy('orden')
                ->take(4)
                ->get();
    }   

    public function activos($sort='asc') {
        return $this->model->newQuery()
                ->whereEnabled(true)
                ->orderBy('fecha',$sort)
                ->orderBy('orden')
                ->get();
    }   
    
    public function destacados() {
        return $this->model->newQuery()
                ->whereEnabled(true)
                ->where('visible_home','!=','NO')
                ->orderBy('visible_home')
                ->orderBy('orden')
                ->get();
    }    
}
