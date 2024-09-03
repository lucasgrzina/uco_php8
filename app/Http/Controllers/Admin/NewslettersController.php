<?php

namespace App\Http\Controllers\Admin;

use Response;
use Illuminate\Http\Request;
use App\Repositories\NewslettersRepository;
use App\Repositories\Criteria\ContactosCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Http\Requests\Admin\CUNewslettersRequest;
use App\Http\Controllers\Admin\CrudAdminController;

class NewslettersController extends CrudAdminController
{
    protected $routePrefix = 'newsletters';
    protected $viewPrefix  = 'admin.newsletters.';
    protected $actionPerms = 'newsletters';

    public function __construct(NewslettersRepository $repo)
    {
        $this->repository = $repo;

        $this->middleware('permission:ver-'.$this->actionPerms, ['only' => ['index','filter','show']]);
        $this->middleware('permission:editar-'.$this->actionPerms, ['only' => ['create','store','edit','update','destroy']]);
    }

    public function index()
    {
        parent::index();
        $this->data['filters']['recibir_info'] = null;
        $this->data['filters']['fecha_desde'] = null;
        $this->data['filters']['fecha_hasta'] = null;
        $this->data['filters']['export_xls'] = true;

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
            $this->repository->pushCriteria(new ContactosCriteria($request));
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
                'email' => null
        ]);

        return view($this->viewPrefix.'cu')->with('data',$this->data);
    }

    public function store(CUNewslettersRequest $request)
    {
        $model = $this->_store($request);
        return $this->sendResponse($model,trans('admin.success'));
    }

    public function edit($id)
    {
        parent::edit($id);

        return view($this->viewPrefix.'cu')->with('data',$this->data);
    }

    public function update($id, CUNewslettersRequest $request)
    {
        $model = $this->_update($id, $request);

        return $this->sendResponse($model,trans('admin.success'));
    }

    public function exportXls(Request $request)
    {

        $data = $this->_filter($request,true);
        $name = 'Newsletter';
        $header = [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'apellido' => 'Apellido',
            'email' => 'Email',
            'recibir_info' => 'Recibir info',
            'created_at' => 'Alta',
        ];
        $format = [
            'recibir_info' => function($col,$row) {
                return $col ? "SI" : "NO";
            }
        ];
        return $this->_exportXls($data,$header,$format,$name);
    }
}
