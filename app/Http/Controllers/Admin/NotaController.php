<?php

namespace App\Http\Controllers\Admin;

use App\Nota;
use Response;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Repositories\NotaRepository;
use App\Http\Requests\Admin\CUNotaRequest;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Http\Controllers\Admin\CrudAdminController;

class NotaController extends CrudAdminController
{
    protected $routePrefix = 'notas';
    protected $viewPrefix  = 'admin.notas.';
    protected $actionPerms = 'notas';

    public function __construct(NotaRepository $repo)
    {
        $this->repository = $repo;

        $this->middleware('permission:ver-'.$this->actionPerms, ['only' => ['index','filter','show']]);
        $this->middleware('permission:editar-'.$this->actionPerms, ['only' => ['create','store','edit','update','destroy']]);
    }

    public function index()
    {
        parent::index();
        $this->data['info'] = [
            'visibleHome' => Arr::pluck(config('constantes.combos.visibleHome',[]), 'value', 'key')
        ];
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
                'visible_home' => 'NO',
                'orden' => Nota::max('orden') + 1
        ]);

        $this->data['info'] = [
            'visibleHome' => config('constantes.combos.visibleHome',[])
        ];

        return view($this->viewPrefix.'cu')->with('data',$this->data);
    }

    public function store(CUNotaRequest $request)
    {
        $model = $this->_store($request);
        return $this->sendResponse($model,trans('admin.success'));
    }

    public function edit($id)
    {
        parent::edit($id);
        $this->data['selectedItem'] = $this->data['selectedItem']->toArray();
        $this->data['selectedItem']['cuerpo'] = $this->data['selectedItem']['cuerpo'] !== null ? $this->data['selectedItem']['cuerpo'] : '';
        $this->data['info'] = [
            'visibleHome' => config('constantes.combos.visibleHome',[])
        ];
        return view($this->viewPrefix.'cu')->with('data',$this->data);
    }

    public function editLang($id, $lang)
    {
        \App::setLocale($lang);

        $model = $this->repository->findWithoutFail($id);

        if (empty($model)) {
            return redirect(route($this->routePrefix.'.index'));
        }

        $this->data = [
            'selectedItem' => array_merge($model->toArray(),['lang' => $lang]),
            'url_save' => route($this->routePrefix.'.update',[$model->id]),
            'url_index' => route($this->routePrefix.'.index')
        ];

        //$this->data['selectedItem'] = array_merge($model->toArray(),['lang' => $lang]);
        $this->data['selectedItem']['cuerpo'] = $this->data['selectedItem']['cuerpo'] !== null ? $this->data['selectedItem']['cuerpo'] : '';


        $this->clearCache();

        return view($this->viewPrefix.'cu')->with('data',$this->data);
    }

    public function update($id, CUNotaRequest $request)
    {
        $model = $this->_update($id, $request);

        return $this->sendResponse($model,trans('admin.success'));
    }
}
