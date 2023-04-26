<?php

namespace App\Http\Controllers\Admin;

use Response;
use App\Services\UPSService;
use Illuminate\Http\Request;
use App\Repositories\PedidoRepository;
use App\Http\Requests\Admin\CUPedidoRequest;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Http\Controllers\Admin\CrudAdminController;

class PedidoController extends CrudAdminController
{
    protected $routePrefix = 'pedidos';
    protected $viewPrefix  = 'admin.pedidos.';
    protected $actionPerms = 'pedidos';

    public function __construct(PedidoRepository $repo)
    {
        $this->repository = $repo;

        $this->middleware('permission:ver-'.$this->actionPerms, ['only' => ['index','filter','show']]);
        $this->middleware('permission:editar-'.$this->actionPerms, ['only' => ['create','store','edit','update','destroy']]);
    }

    public function index()
    {
        parent::index();
        $this->data['url_generar_envio'] = route('pedidos.generar-envio',['_ID_']);
        $this->data['url_ver_etiqueta'] = route('pedidos.ver-etiqueta',['_ID_']);
        return view($this->viewPrefix.'index')->with('data',$this->data);
    }

    public function filter(Request $request)
    {
        try
        {
            $this->repository->pushCriteria(new RequestCriteria($request));
            $collection = $this->repository->with(['items.aniada.vino','registrado'])->paginate($request->get('per_page'))->toArray();

            $this->data = [
                'list' => $collection['data'],
                'paging' => \Arr::only($collection,['total','current_page','last_page'])
            ];

        }
        catch (\Exception $ex)
        {
            return $this->sendError($ex->getMessage(),500);
        }

        return $this->sendResponse($this->data, trans('admin.success'));
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
                'id' => 0
        ]);

        return view($this->viewPrefix.'cu')->with('data',$this->data);
    }

    public function store(CUPedidoRequest $request)
    {
        $model = $this->_store($request);
        return $this->sendResponse($model,trans('admin.success'));
    }

    public function edit($id)
    {
        parent::edit($id);

        return view($this->viewPrefix.'cu')->with('data',$this->data);
    }

    public function update($id, CUPedidoRequest $request)
    {
        $model = $this->_update($id, $request);

        return $this->sendResponse($model,trans('admin.success'));
    }

    public function generarEnvio($id, Request $request, UPSService $upsService)
    {
        try {
            $model = $this->repository->with(['items.aniada.vino'])->findWithoutFail($id);

            $productos = [];
            foreach ($model->items as $item) {
                for($x=0; $x < $item->cantidad; $x++) {
                    array_push($productos, $item);
                }
            }

            $respuesta = $upsService->generarEnvio($id
                ,$model->items
                ,$model->direccion
                ,$model->ciudad
                ,$model->pais->codigo
                ,$model->cp
                ,$model->nombre
                ,$model->apellido
                ,$model->email
                ,''
            );

            $model->ups_tracking_number = $respuesta['tracking_number'];
            $model->ups_etiqueta = $respuesta['etiqueta'];
            $model->ups_info = \Arr::except($respuesta,['tracking_number','etiqueta']);
            $model->save();

            return $this->sendResponse($respuesta,trans('admin.success'));
        } catch (\Exception $ex) {
            return $this->sendError($ex->getMessage(),500);
        }

    }

    public function verEtiqueta($id, Request $request)
    {
        try {
            $model = $this->repository->find($id,['ups_etiqueta','estado_id','id']);
            $raw_image_string = base64_decode($model->ups_etiqueta);
            return response($raw_image_string)->header('Content-Type', 'image/jpg');



            return $this->sendResponse($respuesta,trans('admin.success'));
        } catch (\Exception $ex) {
            return $this->sendError($ex->getMessage(),500);
        }

    }
}
