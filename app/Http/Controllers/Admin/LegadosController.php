<?php

namespace App\Http\Controllers\Admin;

use Response;
use App\Legados;
use Illuminate\Http\Request;
use App\Repositories\LegadosRepository;
use App\Http\Requests\Admin\CULegadosRequest;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Http\Controllers\Admin\CrudAdminController;

class LegadosController extends CrudAdminController
{
    protected $routePrefix = 'legados';
    protected $viewPrefix  = 'admin.legados.';
    protected $actionPerms = 'legados';

    public function __construct(LegadosRepository $repo)
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
                'foto' => null,
                'foto_url' => null,
                'enabled' => true,
                'orden' => Legados::max('orden') + 1
        ]);

        return view($this->viewPrefix.'cu')->with('data',$this->data);
    }

    public function store(CULegadosRequest $request)
    {
        $model = $this->_store($request);
        return $this->sendResponse($model,trans('admin.success'));
    }

    public function edit($id)
    {
        parent::edit($id);

        return view($this->viewPrefix.'cu')->with('data',$this->data);
    }

    public function update($id, CULegadosRequest $request)
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
