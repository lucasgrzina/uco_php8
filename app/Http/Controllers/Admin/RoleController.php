<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\Admin\CreateRoleRequest;
use App\Http\Requests\Admin\UpdateRoleRequest;
use App\Permission;
use App\Repositories\RoleRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class RoleController extends AppBaseController
{
    /** @var  RoleRepository */
    private $roleRepository;
    private $routePrefix = 'roles';

    public function __construct(RoleRepository $roleRepo)
    {
        $this->roleRepository = $roleRepo;
        $this->middleware('permission:ver-roles-y-permisos', ['only' => ['index', 'filter','show']]);
        $this->middleware('permission:editar-roles-y-permisos', ['only' => ['create', 'store','edit','update','destroy']]);
    }

    /**
     * Display a listing of the Role.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $data = [
            'list' => [],
            'action_perms' => 'roles-y-permisos',
            'filters' => [
                'per_page' => config('admin.crud.results_per_page',20),
                'page' => 1,
                'search' => null
            ],
            'paging' => [
                'current_page' => 0,
                'last_page' => 0,
                'total' => 0
            ],
            'loading' => true,
            'url_filter' => route($this->routePrefix.'.filter'),
            'url_create' => route($this->routePrefix.'.create'),
            'url_edit' => route($this->routePrefix.'.edit',['_ID_']),
            'url_destroy' => route($this->routePrefix.'.destroy',['_ID_'])
        ];

        return view('admin.roles.index')->with('data',$data);
    }

    public function filter(Request $request)
    {
        try
        {
            $this->roleRepository->pushCriteria(new RequestCriteria($request));
            $collection = $this->roleRepository->paginate($request->get('per_page'))->toArray();

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
     * Show the form for creating a new Role.
     *
     * @return Response
     */
    public function create()
    {
        $data = [
            'selectedItem' => [
                'id' => 0,
                'name' => '',
                'permissions' => []
            ],
            'info' => [
                'permisos' => $this->prepararPermisos()
            ],
            'url_save' => route($this->routePrefix.'.store'),
            'url_index' => route($this->routePrefix.'.index')
        ];

        return view('admin.roles.cu')->with('data',$data);
    }

    /**
     * Store a newly created Role in storage.
     *
     * @param CreateRoleRequest $request
     *
     * @return Response
     */
    public function store(CreateRoleRequest $request)
    {

        $input = $request->except('permissions');
        $input['guard_name'] = 'admin';

        try
        {
            DB::beginTransaction();

            $model = $this->roleRepository->create($input);
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

    /**
     * Display the specified Role.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified Role.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $model = $this->roleRepository->findWithoutFail($id);

        if (empty($model)) {
            return redirect(route($this->routePrefix.'.index'));
        }

        $permisos = $model->permissions()->pluck('name');
        $model = $model->toArray();
        $model['permissions'] = $permisos;

        $data = [
            'selectedItem' => $model,
            'info' => [
                'permisos' => $this->prepararPermisos($model)
            ],
            'url_save' => route($this->routePrefix.'.update',[$model['id']]),
            'url_index' => route($this->routePrefix.'.index')
        ];

        return view('admin.roles.cu')->with('data',$data);
    }

    /**
     * Update the specified Role in storage.
     *
     * @param  int              $id
     * @param UpdateRoleRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateRoleRequest $request)
    {
        $model = $this->roleRepository->findWithoutFail($id);

        if (empty($model)) {
            return $this->sendError(trans('admin.not_found'));
        }

        try
        {
            DB::beginTransaction();

            $model = $this->roleRepository->update($request->except('permissions'), $id);
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

    /**
     * Remove the specified Role from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $model = $this->roleRepository->findWithoutFail($id);

        if (empty($model)) {
            return $this->sendError(trans('admin.not_found'));
        }

        $this->roleRepository->delete($id);

        return $this->sendResponse(null,trans('admin.success'));
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
}
