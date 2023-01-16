<?php

namespace App\Repositories;

use App\HomeSlider;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class HomeSliderRepository
 * @package App\Repositories
 * @version October 27, 2022, 5:24 pm -03
 *
 * @method HomeSlider findWithoutFail($id, $columns = ['*'])
 * @method HomeSlider find($id, $columns = ['*'])
 * @method HomeSlider first($columns = ['*'])
*/
class HomeSliderRepository extends BaseRepository
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
        return HomeSlider::class;
    }

    public function porSeccion($seccion = 'home') {
        if ($seccion !== 'home') {
            $collection = collect(config('constantes.headers.'.$seccion));

            $result = $collection->map(function ($element) {
                return (object) $element;
            });
            //dd($result);
            return $result->all();
        } else {
            return $this->model->newQuery()->where('enabled',true)->orderBy('orden')->get();
        }

    }
}
