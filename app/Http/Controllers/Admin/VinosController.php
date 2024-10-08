<?php

namespace App\Http\Controllers\Admin;

use App\Vino;
use Response;
use App\VinoImagen;
use Illuminate\Http\Request;
use App\Repositories\VinosRepository;
use App\Http\Requests\Admin\CUVinosRequest;
use App\Repositories\Criteria\VinosCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Http\Controllers\Admin\CrudAdminController;

class VinosController extends CrudAdminController
{
    protected $routePrefix = 'vinos';
    protected $viewPrefix  = 'admin.vinos.';
    protected $actionPerms = 'vinos';

    public function __construct(VinosRepository $repo)
    {
        $this->repository = $repo;

        $this->middleware('permission:ver-'.$this->actionPerms, ['only' => ['index','filter','show']]);
        $this->middleware('permission:editar-'.$this->actionPerms, ['only' => ['create','store','edit','update','destroy']]);
    }

    public function index()
    {
        parent::index();
        $this->data['info'] = [
            'colecciones' => collect(config('constantes.combos.colecciones',[]))->map(function ($value,$key) {
                return trans(str_replace('_trans.','',$value));
            })
        ];
        $this->data['filters']['titulo'] = null;
        $this->data['filters']['peso'] = null;
        $this->data['filters']['largo'] = null;
        $this->data['filters']['ancho'] = null;
        $this->data['filters']['alto'] = null;
        $this->data['filters']['orderBy'] = 'enabled';
        $this->data['filters']['export_xls'] = true;
        $this->data['url_hijos'] = route('aniadas.index',['_ID_']);

        return view($this->viewPrefix.'index')->with('data',$this->data);
    }


    public function filter(Request $request)
    {
        $data = $this->_filter($request, false);

        return $this->sendResponse($data, trans('admin.success'));
    }

    protected function _filter($request,$export=false) {
        try
        {
            $this->repository->pushCriteria(new VinosCriteria($request));
            $this->repository->pushCriteria(new RequestCriteria($request));

            if ($export) {
                $data = $this->repository->all()->toArray();

            } else {
                $collection = $this->repository->paginate($request->get('per_page'))->toArray();

                $data = [
                    'list' => $collection['data'],
                    'paging' => \Arr::only($collection,['total','current_page','last_page'])
                ];
            }

            return $data;

        }
        catch (\Exception $ex)
        {
            return $this->sendError($ex->getMessage(),500);
        }
    }

    public function show($id)
    {
        parent::show($id);

        //$this->data['selectedItem']->load('xxx');

        return view($this->viewPrefix.'show')->with('data', $this->data);
    }

    public function create()
    {
        parent::create();

        data_set($this->data, 'selectedItem', [
            'id' => 0,
            'foto' => null,
            'foto_url' => null,
            'enabled' => true,
            'coleccion' => null,
            'vendible' => false,
            'imagenes' => [],
            'orden' => Vino::max('orden') + 1
        ]);

        $this->data['info'] = [
            'colecciones' => collect(config('constantes.combos.colecciones',[]))->map(function ($value,$key) {
                return trans(str_replace('_trans.','',$value));
            })
        ];

        return view($this->viewPrefix.'cu')->with('data',$this->data);
    }

    public function store(CUVinosRequest $request)
    {
        try
        {
            \DB::beginTransaction();
            $model = $this->_store($request->except('imagenes'),true);
            $model = $this->syncImages($model,$request->get('imagenes'),false);
            \DB::commit();

            //CacheHelper:: clearKeys('success');
        }
        catch (\Exception $ex)
        {
            \DB::rollback();
            throw $ex;
        }
        return $this->sendResponse($model,trans('admin.success'));
    }

    public function edit($id)
    {
        parent::edit($id);
        $this->data['selectedItem']->load('imagenes');
        $this->data['info'] = [
            'colecciones' => collect(config('constantes.combos.colecciones',[]))->map(function ($value,$key) {
                return trans(str_replace('_trans.','',$value));
            })
        ];
        return view($this->viewPrefix.'cu')->with('data',$this->data);
    }

    public function update($id, CUVinosRequest $request)
    {

        try
        {
            \DB::beginTransaction();;
            $model = $this->_update($id,$request->except('imagenes'),true);

            if (!$request->has('lang'))
            {
                $model = $this->syncImages($model,$request->get('imagenes'),true);

            }

            \DB::commit();

            //CacheHelper:: clearKeys('success');
        }
        catch (\Exception $ex)
        {
            \DB::rollback();
            throw $ex;
        }

        return $this->sendResponse($model,trans('admin.success'));
    }

    protected function syncImages($model, $files, $isUpdate = false)
    {
        foreach ($files as $file)
        {
            if ($file['id'] > 0)
            {
                $image = VinoImagen::find($file['id']);
            }
            else
            {
                $image = new VinoImagen();
            }

            if (\Arr::get($file,'delete',false))
            {
                if ($file['id'] > 0)
                {
                    $image->delete();
                }
            }
            else
            {
                $image->fill(\Arr::only($file,['filename','orden']));
                $model->imagenes()->save($image);
            }

        }

        return $model;
    }

    public function exportXls(Request $request)
    {
        $data = $this->_filter($request,true);
        $name = 'Vinos';
        $header = [
            'id' => 'ID',
            'titulo' => 'Titulo',
            'peso' => 'Peso',
            'largo' => 'Largo',
            'ancho' => 'Ancho',
            'alto' => 'Alto',
        ];
        $format = [
            /*'registrado_id' => function($col,$row) {
                return $row['registrado']['nombre'] . ' ' . $row['registrado']['apellido'];
            }*/
        ];
        return $this->_exportXls($data,$header,$format,$name);
    }
}
