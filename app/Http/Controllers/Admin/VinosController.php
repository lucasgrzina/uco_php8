<?php

namespace App\Http\Controllers\Admin;

use Response;
use App\Vino;
use Illuminate\Http\Request;
use App\Repositories\VinosRepository;
use App\Http\Requests\Admin\CUVinosRequest;
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
        $this->data['filters']['orderBy'] = 'enabled';
        $this->data['url_hijos'] = route('aniadas.index',['_ID_']);

        return view($this->viewPrefix.'index')->with('data',$this->data);
    }

    public function filter(Request $request)
    {
        try
        {
            $this->repository->pushCriteria(new RequestCriteria($request));
            $collection = $this->repository->with('updater')->paginate($request->get('per_page'))->toArray();

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
            'id' => 0,
            'foto' => null,
            'foto_url' => null,
            'enabled' => true,
            'coleccion' => null,
            'vendible' => false,
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
        $model = $this->_store($request);
        return $this->sendResponse($model,trans('admin.success'));
    }

    public function edit($id)
    {
        parent::edit($id);

        $this->data['info'] = [
            'colecciones' => collect(config('constantes.combos.colecciones',[]))->map(function ($value,$key) {
                return trans(str_replace('_trans.','',$value));
            })
        ];
        return view($this->viewPrefix.'cu')->with('data',$this->data);
    }

    public function update($id, CUVinosRequest $request)
    {
        $model = $this->_update($id, $request);

        return $this->sendResponse($model,trans('admin.success'));
    }
}
