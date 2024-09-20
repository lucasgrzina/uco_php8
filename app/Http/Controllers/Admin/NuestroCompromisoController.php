<?php

namespace App\Http\Controllers\Admin;

use Response;
use App\NuestroCompromiso;
use Illuminate\Http\Request;

use App\Http\Requests\Admin\CULegadosRequest;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\NuestroCompromisoRepository;
use App\Http\Controllers\Admin\CrudAdminController;
use App\Http\Requests\Admin\CUNuestroCompromisoRequest;

class NuestroCompromisoController extends CrudAdminController
{
    protected $routePrefix = 'nuestro-compromiso';
    protected $viewPrefix  = 'admin.nuestro_compromiso.';
    protected $actionPerms = 'nuestro-compromiso';

    public function __construct(NuestroCompromisoRepository $repo)
    {
        $this->repository = $repo;

        $this->middleware('permission:ver-'.$this->actionPerms, ['only' => ['index','filter','show']]);
        $this->middleware('permission:editar-'.$this->actionPerms, ['only' => ['create','store','edit','update','destroy']]);
    }

    public function index()
    {
        parent::index();

        return view($this->viewPrefix.'index')->with('data',$this->data);
    }

    public function filter(Request $request)
    {
        try
        {
            $this->repository->pushCriteria(new RequestCriteria($request));
            $collection = $this->repository->paginate($request->get('per_page'))->toArray();

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
                'titulo' => null,
                'imagen_home' => null,
                'imagen_home_url' => null,
                'imagen_interna' => null,
                'imagen_interna_url' => null,

        ]);

        return view($this->viewPrefix.'cu')->with('data',$this->data);
    }

    public function store(CUNuestroCompromisoRequest $request)
    {
        $model = $this->_store($request);
        return $this->sendResponse($model,trans('admin.success'));
    }

    public function edit($id)
    {
        parent::edit($id);

        return view($this->viewPrefix.'cu')->with('data',$this->data);
    }

    public function update($id, CUNuestroCompromisoRequest $request)
    {
        $model = $this->_update($id, $request);
        return $this->sendResponse($model,trans('admin.success'));
    }

    public function editLang($id, $lang)
    {
        \App::setLocale($lang);

        $model = $this->repository->findWithoutFail($id);

        if (empty($model)) {
            return redirect()->back();
        }

        $this->data = [
            'selectedItem' => array_merge($model->toArray(),['lang' => $lang]),
            'url_save' => route($this->routePrefix.'.update',[$model->id]),
            'url_index' => route($this->routePrefix.'.index',[$model->vino_id])
        ];


        $this->data['selectedItem']['cuerpo'] = $this->data['selectedItem']['cuerpo'] !== null ? $this->data['selectedItem']['cuerpo'] : '';

        $this->clearCache();

        return view($this->viewPrefix.'cu')->with('data',$this->data);
    }
}
