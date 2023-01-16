<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\UserDataTable;
use App\Exports\GeneralExport;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\Admin;
use App\Http\Requests\Admin\CreateUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Pais;
use App\Paises;
use App\Permission;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Spatie\Permission\Models\Role;

class UserController extends AppBaseController
{
    /** @var  UserRepository */
    private $userRepository;
    private $routePrefix = 'usuarios';
    private $role_repo = null;

    public function __construct(UserRepository $userRepo,Request $request, RoleRepository $role_repo)
    {
        $this->userRepository = $userRepo;
        $this->role_repo = $role_repo;

        $this->middleware('permission:ver-usuarios', ['only' => ['index', 'filter','show']]);
        $this->middleware('permission:editar-usuarios', ['only' => ['create', 'store','destroy','editarPermisos','guardarPermisos','edit','update']]);
    }

    /**
     * Display a listing of the User.
     *
     * @param UserDataTable $userDataTable
     * @return Response
     */
    public function index(Request $request)
    {

        $data = [
            'list' => [],
            'action_perms' => 'usuarios',
            'filters' => [
                'per_page' => config('admin.crud.results_per_page',20),
                'page' => 1,
                'search' => null,
                'rol' => null,
                'orderBy' => 'apellido',
                'sortedBy' => 'asc',
                'enabled' => 1,
                'export_xls' => true
            ],
            'paging' => [
                'current_page' => 0,
                'last_page' => 0,
                'total' => 0
            ],
            'info' => [
                'roles' => $this->role_repo->getActivos()
            ],
            'loading' => true,
            'url_filter' => route($this->routePrefix.'.filter'),
            'url_create' => route($this->routePrefix.'.create'),
            'url_edit' => route($this->routePrefix.'.edit',['_ID_']),
            'url_editar_permisos' => route($this->routePrefix.'.editar-permisos',['_ID_']),
            'url_show' => route($this->routePrefix.'.show',['_ID_']),
            'url_change_enabled' => route($this->routePrefix.'.change-enabled'),
            'url_destroy' => route($this->routePrefix.'.destroy',['_ID_']),
            'url_export' => route($this->routePrefix.'.export',['_TYPE_'])
        ];
        $data['stored_filters_key'] = 'usuarios';
        return view('admin.users.index')->with('data',$data);
    }

    public function filter(Request $request)
    {
        try
        {
            $this->userRepository->pushCriteria(new \App\Repositories\Criteria\EnabledCriteria($request));
            $this->userRepository->pushCriteria(new RequestCriteria($request));
            $this->userRepository->pushCriteria(new \App\Repositories\Criteria\UserCriteria($request));
            $collection = $this->userRepository->with(['roles'])->paginate($request->get('per_page'))->toArray();

            $data = [
                'list' => $collection['data'],
                'paging' => \Arr::only($collection,['total','current_page','last_page'])
            ];

        }
        catch (\Exception $ex)
        {
            return $this->sendError($ex->getMessage(),500);
        }

        return $this->sendResponse($data, trans('admin.success'));
    }

    /**
     * Show the form for creating a new User.
     *
     * @return Response
     */
    public function create()
    {


        $data = [
            'selectedItem' => [
                'id' => 0,
                'role_id' => null,
                'enabled' => true,
            ],
            'info' => [
                'roles' => $this->role_repo->getActivos(),
            ],
            'url_save' => route($this->routePrefix.'.store'),
            'url_index' => route($this->routePrefix.'.index')
        ];



        return view('admin.users.cu')->with('data',$data);
    }

    /**
     * Store a newly created User in storage.
     *
     * @param CreateUserRequest $request
     *
     * @return Response
     */

    public function store(CreateUserRequest $request)
    {
        try
        {
            DB::beginTransaction();

            $input = $request->all();

            $model = $this->userRepository->create($input);

            if ($request->has('role_id')) {
                $role = Role::whereId($request->get('role_id'))->firstOrFail();
                $model->assignRole($role); //Assigning role to user
            }

            DB::commit();
        }
        catch (\Exception $ex)
        {
            DB::rollBack();
            return $this->sendError($ex->getMessage());
        }

        return $this->sendResponse($model,trans('admin.success'));
    }

    /**
     * Display the specified User.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
       $model = $this->userRepository->with(['roles'])->findWithoutFail($id);

        if (empty($model)) {
            return redirect(route($this->routePrefix.'.index'));
        }

        $permisos = $model->getAllPermissions()->pluck('name');
        $model = $model->toArray();
        $model['permissions'] = $permisos;

        $data = [
            'selectedItem' => $model,
            'info' => [
                'permisos' => $this->prepararPermisos($model),
            ],
            'action_perms' => 'usuarios',
            'url_edit' => route($this->routePrefix.'.edit',[$model['id']]),
            'url_index' => route($this->routePrefix.'.index')
        ];

        return view('admin.users.show')->with('data', $data);
    }

    /**
     * Show the form for editing the specified User.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $model = $this->userRepository->findWithoutFail($id);

        if (empty($model)) {
            return redirect(route($this->routePrefix.'.index'));
        }

        $role_id = $model->roles()->first()->id;

        $model = $model->toArray();
        $model['role_id'] = $role_id;

        $data = [
            'selectedItem' => $model,
            'info' => [
                'roles' => $this->role_repo->getActivos(),
            ],
            'url_save' => route($this->routePrefix.'.update',[$model['id']]),
            'url_index' => route($this->routePrefix.'.index')
        ];

        return view('admin.users.cu')->with('data',$data);
    }

    public function editarPermisos($id)
    {

        $model = $this->userRepository->findWithoutFail($id);

        if (empty($model)) {
            return redirect(route($this->routePrefix.'.index'));
        }

        $permisos = $model->getAllPermissions()->pluck('name');
        $permisosRol = $model->getPermissionsViaRoles()->pluck('name');
        $model = $model->toArray();
        $model['permissions'] = $permisos;
        $model['permisos_rol'] = $permisosRol;

        $data = [
            'selectedItem' => $model,
            'info' => [
                'permisos' => $this->prepararPermisos($model)
            ],
            'action_perms' => 'usuarios',
            'url_save' => route($this->routePrefix.'.guardar-permisos',[$model['id']]),
            'url_index' => route($this->routePrefix.'.index')
        ];

        return view('admin.users.editar-permisos')->with('data',$data);
    }

    /**
     * Update the specified User in storage.
     *
     * @param  int              $id
     * @param UpdateUserRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateUserRequest $request)
    {
        $model = $this->userRepository->findWithoutFail($id);

        if (empty($model)) {
            return $this->sendError(trans('admin.not_found'));
        }

        try
        {
            DB::beginTransaction();

            $model = $this->userRepository->update($request->except('password','password_confirmation'), $id);

            if ($request->get('password',false))
            {
                $model->password = $request->get('password');
                $model->save();
            }

            $model->roles()->sync([$request->get('role_id')]);

            DB::commit();
        }
        catch (\Exception $ex)
        {
            DB::rollBack();
            return $this->sendError($ex->getMessage());
        }

        return $this->sendResponse($model,trans('admin.success'));
    }

    public function guardarPermisos($id, Request $request)
    {
        $model = $this->userRepository->findWithoutFail($id);

        if (empty($model)) {
            return $this->sendError(trans('admin.not_found'));
        }

        try
        {
            DB::beginTransaction();
            //\Log::info($request->get('permissions'));

            $model->syncPermissions($request->get('permissions'));

            DB::commit();
        }
        catch (\Exception $ex)
        {
            DB::rollback();
            return $this->sendError($ex->getMessage());
        }
        return $this->sendResponse($model,trans('admin.success'));
    }

    public function changeEnabled(Request $request)
    {
        $input = $request->only('enabled');

        $model = $this->userRepository->findWithoutFail($request->get('id'));

        if (empty($model)) {
            return $this->sendError(trans('admin.not_found'));
        }

        $model = $this->userRepository->update($input, $request->get('id'));

        return $this->sendResponse(null,trans('admin.success'));
    }


    /**
     * Remove the specified FaqCategori from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $model = $this->userRepository->findWithoutFail($id);

        if (empty($model)) {
            return $this->sendError(trans('admin.not_found'));
        }

        $this->userRepository->delete($id);

        return $this->sendResponse(null,trans('admin.success'));
    }

    public function export(Request $request,$type = 'xlsx')
    {
        $request->merge(['page' => 1,'per_page' => 99999]);

        $this->userRepository->pushCriteria(new \App\Repositories\Criteria\EnabledCriteria($request));
        $this->userRepository->pushCriteria(new \App\Repositories\Criteria\UserCriteria($request));
        $this->userRepository->pushCriteria(new RequestCriteria($request));
        $data = $this->userRepository->with([
            'roles' => function($q) {
                $q->select('name','id');
            }
        ])->all()->toArray();


        $header = [
            'nombre' => 'Nombre',
            'apellido' => 'Apellido',
            'pais_id' => 'Pais',
            'roles' => 'Rol',
            'email' => 'Email',
            'enabled' => 'Estado'
        ];

        $format = [
            'roles' => function($value) {
                return $value[0]['name'];
            },

            'enabled' => function($value) {
                return $value == 1 ? 'Usuario activo' : 'Usuario inactivo';
            }
        ];

        return $this->_exportXls($data,$header,$format,'usuarios');
    }

    protected function prepararPermisos()
    {
        $todos = Permission::whereGuardName('admin')->orderBy('order')->orderBy('group_name')->orderBy('action')->select('name','group_id','group_name','action')->get();

        $seleccionados = [];

        $return = [];
        foreach ($todos as $perm)
        {
            if (!array_key_exists($perm->group_name, $return))
            {
                $return[$perm->group_name] = [];
            }

            $return[$perm->group_name][] = $perm->name;
        }
        return $return;
    }

    protected function _exportXls($data,$header = [],$format = [],$name='export')
    {
        $_name = $name . '_'. Carbon::now()->format('Ymd') . '.xlsx';
        return Excel::download(new GeneralExport($data,$header,$format),$_name);
    }
}
