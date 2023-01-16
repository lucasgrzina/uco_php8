<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Restaurantes;
use App\Imports\GeneralImport;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class RestaurantesRepository
 * @package App\Repositories
 * @version June 11, 2021, 7:50 pm -03
 *
 * @method Restaurantes findWithoutFail($id, $columns = ['*'])
 * @method Restaurantes find($id, $columns = ['*'])
 * @method Restaurantes first($columns = ['*'])
*/
class RestaurantesRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'nombre' => 'like',
        'provincia' => 'like',
        'localidad' => 'like',
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Restaurantes::class;
    }

    public function importar ($filePath) {
        //\DB::statement("SET GLOBAL max_allowed_packet=104857600;");
        $disk = 'tmp';
        if (\StorageHelper::existe($filePath)) {
            try
            {
                $data = [];
                $ahora = now()->format('Y-m-d H:i:s');
                \DB::beginTransaction();
                $contenido = (new GeneralImport)->toCollection($filePath);
                if (count($contenido) > 0) {
                    foreach ($contenido[0] as $item) {

                        if ($item['nombre']) {
                            Restaurantes::create(array_merge($item->toArray(),[
                                'created_at' => now()->format('Y-m-d H:i:s'),
                                'updated_at' => now()->format('Y-m-d H:i:s'),
                            ]));    
                        }
                    }
                }
                \DB::commit();
                
                return true;            
            }
            catch(\Exception $ex)
            {
                \DB::rollback();
                \Log::info($ex->getMessage());
                throw $ex;
            }             
           
        }
    }
      
    public function obtenerListado($segmento) {
        $query = $this->model->newQuery()
                ->whereActivo(true)
                ->whereSegmento($segmento)
                ->orderBy('orden');

        return $query->get();

    }
}
