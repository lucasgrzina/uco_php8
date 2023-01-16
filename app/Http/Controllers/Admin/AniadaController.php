<?php

namespace App\Http\Controllers\Admin;

use App\Vino;
use Response;
use Illuminate\Http\Request;
use App\Repositories\AniadaRepository;
use App\Http\Requests\Admin\CUAniadaRequest;

use Prettus\Repository\Criteria\RequestCriteria;
use App\Http\Controllers\Admin\CrudAdminParentController;

class AniadaController extends CrudAdminParentController
{
    protected $routePrefix = 'aniadas';
    protected $viewPrefix  = 'admin.aniadas.';
    protected $actionPerms = 'aniadas';

    public function __construct(AniadaRepository $repo)
    {
        $this->repository = $repo;

        $this->middleware('permission:ver-'.$this->actionPerms, ['only' => ['index','filter','show']]);
        $this->middleware('permission:editar-'.$this->actionPerms, ['only' => ['create','store','edit','update','destroy']]);
    }

    public function index($parentId)
    {
        parent::index($parentId);
        $this->data['parent'] = Vino::find($parentId);
        $this->data['url_back'] = route('vinos.index');
        return view($this->viewPrefix.'index')->with('data',$this->data);
    }

    public function filter($parentId, Request $request)
    {
        try
        {
            $this->repository->pushCriteria(new RequestCriteria($request));
            $collection = $this->repository->with(['updater','vino'])->scopeQuery(function($q) use($parentId){
                return $q->whereVinoId($parentId);
            })->paginate($request->get('per_page'))->toArray();

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

    public function show($parentId,$id)
    {
        parent::show($id);

        //$this->data['selectedItem']->load('xxx');

        return view($this->viewPrefix.'show')->with('data', $this->data);
    }

    public function create($parentId)
    {
        parent::create($parentId);

        $parent = Vino::find($parentId);

        data_set($this->data, 'selectedItem', [
                'id' => 0,
                'vino_id' => $parentId,
                'vino' => $parent,
                'ficha' => null,
                'ficha_url' => null,
                'enabled' => true,
        ]);

        return view($this->viewPrefix.'cu')->with('data',$this->data);
    }

    public function store($parentId,CUAniadaRequest $request)
    {
        $model = $this->_store($request->except('vino'));
        return $this->sendResponse($model,trans('admin.success'));
    }

    public function editLang($id, $lang)
    {
        \App::setLocale($lang);

        $model = $this->repository->findWithoutFail($id);
        $model->load('vino');
        if (empty($model)) {
            return redirect()->back();
        }

        $this->data = [
            'selectedItem' => array_merge($model->toArray(),['lang' => $lang]),
            'url_save' => route($this->routePrefix.'.update',[$model->id]),
            'url_index' => route($this->routePrefix.'.index',[$model->vino_id])
        ];


        $this->clearCache();

        return view($this->viewPrefix.'cu')->with('data',$this->data);
    }

    public function edit($parentId,$id)
    {
        parent::edit($parentId,$id);
        $this->data['selectedItem']->load(['vino']);

        return view($this->viewPrefix.'cu')->with('data',$this->data);
    }

    public function update($id, CUAniadaRequest $request)
    {
        $model = $this->_update($id, $request);

        return $this->sendResponse($model,trans('admin.success'));
    }
}
