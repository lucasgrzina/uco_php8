<?php

namespace App\Http\Controllers\Admin;

use Response;
use App\HomeSlider;
use Illuminate\Http\Request;
use App\Repositories\HomeSliderRepository;
use App\Http\Requests\Admin\CUHomeSliderRequest;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Criteria\HomeSliderCriteria;
use App\Http\Controllers\Admin\CrudAdminController;

class HomeSliderController extends CrudAdminController
{
    protected $routePrefix = 'home-sliders';
    protected $viewPrefix  = 'admin.home_sliders.';
    protected $actionPerms = 'home-sliders';

    public function __construct(HomeSliderRepository $repo)
    {
        $this->repository = $repo;

        $this->middleware('permission:ver-'.$this->actionPerms, ['only' => ['index','filter','show']]);
        $this->middleware('permission:editar-'.$this->actionPerms, ['only' => ['create','store','edit','update','destroy']]);
    }

    public function index()
    {
        parent::index();
        $this->data['info'] = [
            'secciones' => array_keys(config('constantes.headers'))
        ];
        $this->data['filters']['seccion'] = 'home';
        return view($this->viewPrefix.'index')->with('data',$this->data);
    }

    public function filter(Request $request)
    {
        try
        {
            $this->repository->pushCriteria(new RequestCriteria($request));
            $this->repository->pushCriteria(new HomeSliderCriteria($request));
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
                'imagen_mobile' => null,
                'imagen_mobile_url' => null,
                'imagen_desktop' => null,
                'imagen_desktop_url' => null,
                'video' => null,
                'enabled' => true,
                'orden' => HomeSlider::max('orden') + 1,
                'seccion' => 'home'
        ]);

        $this->data['info'] = [
            'secciones' => array_keys(config('constantes.headers'))
        ];

        return view($this->viewPrefix.'cu')->with('data',$this->data);
    }

    public function store(CUHomeSliderRequest $request)
    {
        $model = $this->_store($request);
        return $this->sendResponse($model,trans('admin.success'));
    }

    public function edit($id)
    {
        parent::edit($id);
        $this->data['info'] = [
            'secciones' => array_keys(config('constantes.headers'))
        ];
        return view($this->viewPrefix.'cu')->with('data',$this->data);
    }


    public function update($id, CUHomeSliderRequest $request)
    {
        $model = $this->_update($id, $request);

        return $this->sendResponse($model,trans('admin.success'));
    }
}
