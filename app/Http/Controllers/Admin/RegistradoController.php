<?php

namespace App\Http\Controllers\Admin;

use Response;
use Carbon\Carbon;
use App\Registrado;
use Illuminate\Http\Request;
use App\Exports\GeneralExport;
use App\Mail\SendCredentialsMail;
use Maatwebsite\Excel\Facades\Excel;
use App\Repositories\RegistradoRepository;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\Admin\CURegistradoRequest;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Criteria\RegistradoCriteria;

class RegistradoController extends AppBaseController
{
    /** @var  registradoRepository */
    private $registradoRepository;
    private $routePrefix = 'registrados';
    protected $actionPerms = 'usuarios';
    //private

    public function __construct(RegistradoRepository $registeredRepo)
    {
        $this->registradoRepository = $registeredRepo;
    }

    /**
     * Display a listing of the Registered.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $data = [
            'list' => [],
            'filters' => [
                'per_page' => config('admin.crud.results_per_page',20),
                'page' => 1,
                'search' => null,
                'confirmado' => null,
                'orderBy' => 'id',
                'sortedBy' => 'desc',
                'export_xls' => true
            ],
            'paging' => [
                'current_page' => 0,
                'last_page' => 0,
                'total' => 0
            ],
            'loading' => true,
            'confirmando' => false,
            'action_perms' => $this->actionPerms,
            'perms' => auth()->user()->getAllPermissions()->pluck('name'),
            'roles' => auth()->user()->getRoleNames(),

            'url_filter' => route($this->routePrefix.'.filter'),
            'url_create' => route($this->routePrefix.'.create'),
            'url_edit' => route($this->routePrefix.'.edit',['_ID_']),
            'url_show' => route($this->routePrefix.'.show',['_ID_']),
            'url_change_enabled' => route($this->routePrefix.'.change-enabled'),
            //'url_enable_registered' => route($this->routePrefix.'.enable-registered'),
            'url_destroy' => route($this->routePrefix.'.destroy',['_ID_']),
            'url_reset_password' => url('password/email'),
            'url_export' => route($this->routePrefix.'.export')
        ];




        $data['info'] = [
            //'paises' => Paises::whereEnabled(true)->orderBy('nombre')->get()
        ];

        $data['filters']['pais_id'] = null;


        return view('admin.registrados.index')->with('data',$data);
    }

    public function filter(Request $request)
    {
        $data = $this->_filter($request, false);

        return $this->sendResponse($data, trans('admin.success'));
    }

    protected function _filter($request,$export=false) {
        try
        {
            $this->registradoRepository->pushCriteria(new RegistradoCriteria($request));
            $this->registradoRepository->pushCriteria(new RequestCriteria($request));

            if ($export) {
                $data = $this->registradoRepository->all()->toArray();

            } else {
                $collection = $this->registradoRepository->paginate($request->get('per_page'))->toArray();

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

    /**
     * Show the form for creating a new Registered.
     *
     * @return Response
     */
    public function create()
    {

        $data = [
            'selectedItem' => [
                'id' => 0,
                'enabled' => true,
                'send_credentials' => false
            ],
            'info' => [
            ],
            'url_save' => route($this->routePrefix.'.store'),
            'url_index' => route($this->routePrefix.'.index')
        ];

        return view('admin.registrados.cu')->with('data',$data);
    }

    /**
     * Store a newly created Registered in storage.
     *
     * @param CreateRegisteredRequest $request
     *
     * @return Response
     */
    public function store(CURegistradoRequest $request)
    {
        $input = $request->all();

        $model = $this->registradoRepository->create($input);

        try
        {
            if ($request->get('send_credentials',false))
            {
                //\Mail::queue(new \App\Mail\SendCredentialsMail($model,$request->get('password')));
            }
        }
        catch (\Exception $e)
        {
            \Log::info($e->getMessage());
        }

        return $this->sendResponse($model,trans('admin.success'));
    }

    /**
     * Display the specified Registered.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
       $model = $this->registradoRepository->findWithoutFail($id);

        if (empty($model)) {
            return redirect(route($this->routePrefix.'.index'));
        }

        $data = [
            'selectedItem' => $model,
            'url_edit' => route($this->routePrefix.'.edit',[$model->id]),
            'url_index' => route($this->routePrefix.'.index')
        ];

        return view('admin.registrados.show')->with('data', $data);
    }

    /**
     * Show the form for editing the specified Registered.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $model = $this->registradoRepository->findWithoutFail($id);

        if (empty($model)) {
            return redirect(route($this->routePrefix.'.index'));
        }

        $data = [
            'selectedItem' => $model,
            'info' => [
            ],
            'url_save' => route($this->routePrefix.'.update',[$model->id]),
            'url_index' => route($this->routePrefix.'.index')
        ];

        return view('admin.registrados.cu')->with('data',$data);
    }

    /**
     * Update the specified Registered in storage.
     *
     * @param  int              $id
     * @param UpdateRegisteredRequest $request
     *
     * @return Response
     */
    public function update($id, CURegistradoRequest $request)
    {
        $input = $request->except('password','password_confirmation');

        $model = $this->registradoRepository->findWithoutFail($id);

        if (empty($model)) {
            return $this->sendError(trans('admin.not_found'));
        }

        $model = $this->registradoRepository->update($input, $id);

        if ($request->get('password',false))
        {
            $model->password = $request->get('password');
            $model->save();
        }

        return $this->sendResponse($model,trans('admin.success'));
    }

    public function changeEnabled(Request $request)
    {
        $input = $request->only('confirmado');

        $model = $this->registradoRepository->findWithoutFail($request->get('id'));

        if (empty($model)) {
            return $this->sendError(trans('admin.not_found'));
        }

        $model = $this->registradoRepository->update($input, $request->get('id'));

        return $this->sendResponse(null,trans('admin.success'));
    }

    public function enableRegistered(Request $request)
    {
        $input = $request->only('confirmado');

        $model = $this->registradoRepository->findWithoutFail($request->get('id'));

        if (empty($model)) {
            return $this->sendError(trans('admin.not_found'));
        }

        $model = $this->registradoRepository->update($input, $request->get('id'));


        if ($input['confirmado'])
        {
            $model->enviarNotificacionRegistroConfirmado();
            //\Mail::queue(new \App\Mail\SendCredentialsMail($model,null));
        }


        return $this->sendResponse(null,trans('admin.success'));
    }

    /**
     * Remove the specified Registered from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $model = $this->registradoRepository->findWithoutFail($id);

        if (empty($model)) {
            return $this->sendError(trans('admin.not_found'));
        }

        $this->registradoRepository->delete($id);

        return $this->sendResponse(null,trans('admin.success'));
    }


    public function rechazadosAPendientes () {
        Registrado::whereConfirmado(0)->update(['confirmado' => 2]);
    }

    public function exportXls(Request $request)
    {
        $data = $this->_filter($request,true);
        $name = 'Registrados';
        $header = [
            'id' => 'ID',
            'usuario' => 'Usuario',
            'email' => 'Email',
            'created_at' => 'Alta',
        ];
        $format = [
            /*'registrado_id' => function($col,$row) {
                return $row['registrado']['nombre'] . ' ' . $row['registrado']['apellido'];
            }*/
        ];
        return $this->_exportXls($data,$header,$format,$name);
    }

    protected function _exportXls($data,$header = [],$format = [],$name='export')
    {
        $_name = $name . '_'. Carbon::now()->format('Ymd') . '.xlsx';
        return Excel::download(new GeneralExport($data,$header,$format),$_name);
    }
}
