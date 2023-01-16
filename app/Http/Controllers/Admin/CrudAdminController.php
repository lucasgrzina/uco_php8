<?php

namespace App\Http\Controllers\Admin;

use App\Exports\GeneralExport;
use App\Helpers\CacheHelper;
use App\Http\Controllers\AppBaseController;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CrudAdminController extends AppBaseController
{
    protected $repository;
    protected $data = [];
    protected $cacheKeys = [];

    public function index()
    {
        $this->data = [
	            'list' => [],
	            'filters' => [
	                'per_page' => config('admin.crud.results_per_page',20),
	                'page' => 1,
	                'search' => null,
                    'orderBy' => 'id',
                    'sortedBy' => 'desc',
                    'export_xls' => false
	            ],
	            'paging' => [
	                'current_page' => 0,
	                'last_page' => 0,
	                'total' => 0
	            ],
                'action_perms' => $this->actionPerms,
                'perms' => auth()->user()->getAllPermissions()->pluck('name'),
                'roles' => auth()->user()->getRoleNames(),
	            'loading' => true,
	            'url_filter' => route($this->routePrefix.'.filter'),
	            'url_create' => route($this->routePrefix.'.create'),
	            'url_edit' => route($this->routePrefix.'.edit',['_ID_']),
	            'url_show' => route($this->routePrefix.'.show',['_ID_']),
	            'url_destroy' => route($this->routePrefix.'.destroy',['_ID_'])
        ];

        if (\Route::has($this->routePrefix.'.export'))
        {
            $this->data = \Arr::add($this->data,'url_export',route($this->routePrefix.'.export',['_TYPE_']));
        }
        if (\Route::has($this->routePrefix.'.change-enabled'))
        {
            $this->data = \Arr::add($this->data,'url_change_enabled',route($this->routePrefix.'.change-enabled'));
        }
    	if (\Route::has($this->routePrefix.'.edit-lang'))
    	{
    		$this->data = \Arr::add($this->data,'url_edit_lang',route($this->routePrefix.'.edit-lang',['_ID_','_LANG_']));
    	}
    }

    public function create()
    {
        $this->data = [
            'selectedItem' => [
                'id' => 0,
                'enabled' => true
            ],
            'action_perms' => $this->actionPerms,
            'perms' => auth()->user()->getAllPermissions()->pluck('name'),
            'roles' => auth()->user()->getRoleNames(),
            'url_save' => route($this->routePrefix.'.store'),
            'url_index' => route($this->routePrefix.'.index')
        ];
    }

    protected function _store($request,$inTransaction=false)
    {
        if (is_array($request))
        {
            $input = $request;
        }
        else
        {
            $input = $request->all();
        }

        if ($inTransaction)
        {
            $model = $this->repository->create($input);
            $this->clearCache();
            return $model;
        }

        try
        {
            \DB::beginTransaction();;
            $model = $this->repository->create($input);
            \DB::commit();
        }
        catch (\Exception $ex)
        {
            \DB::rollback();
            throw $ex;
        }

        $this->clearCache();

        return $model;
    }

    public function show($id)
    {
       $model = $this->repository->findWithoutFail($id);

        if (empty($model)) {
            return redirect(route($this->routePrefix.'.index'));
        }

        $this->data = [
            'selectedItem' => $model,
            'action_perms' => $this->actionPerms,
            'perms' => auth()->user()->getAllPermissions()->pluck('name'),
            'roles' => auth()->user()->getRoleNames(),
            'url_edit' => route($this->routePrefix.'.edit',[$model->id]),
            'url_index' => route($this->routePrefix.'.index')
        ];


    }

    public function edit($id)
    {
        $model = $this->repository->findWithoutFail($id);

        if (empty($model)) {
            return redirect(route($this->routePrefix.'.index'));
        }

        $this->data = [
            'selectedItem' => $model,
            'action_perms' => $this->actionPerms,
            'perms' => auth()->user()->getAllPermissions()->pluck('name'),
            'roles' => auth()->user()->getRoleNames(),
            'url_save' => route($this->routePrefix.'.update',[$model->id]),
            'url_index' => route($this->routePrefix.'.index')
        ];
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

        $this->clearCache();

        return view($this->viewPrefix.'cu')->with('data',$this->data);
    }

    protected function _update($id, $request, $inTransaction = false)
    {
        if (is_array($request))
        {
            $input = $request;
        }
        else
        {
            $input = $request->all();
        }

        $model = $this->repository->findWithoutFail($id);

        if (empty($model)) {
            return $this->sendError(trans('admin.not_found'));
        }

        if (\Arr::has($input,'lang'))
        {
            $input = [
                $input['lang'] => \Arr::only($input,$model->translatedAttributes)
            ];
        }

        if ($inTransaction)
        {
            $model->fill($input)->save();
            $this->clearCache();
            return $model;
        }

        try
        {
            \DB::beginTransaction();
            $model->fill($input)->save();
            \DB::commit();
        }
        catch (\Exception $ex)
        {
            \DB::rollback();
            throw $ex;
        }

        $this->clearCache();

        return $model;
    }

    public function changeEnabled(Request $request)
    {
        $input = $request->only('enabled');

        $model = $this->repository->findWithoutFail($request->get('id'));

        if (empty($model)) {
            return $this->sendError(trans('admin.not_found'));
        }

        $model = $this->repository->update($input, $request->get('id'));

        $this->clearCache();

        return $this->sendResponse(null,trans('admin.success'));
    }

    public function destroy($id)
    {
        try
        {
            \DB::beginTransaction();
            $model = $this->repository->findWithoutFail($id);
            $this->_destroy($model);
            \DB::commit();
        }
        catch (\Exception $e)
        {
            \DB::rollback();
            return $this->sendError($e->getMessage());
        }

        $this->clearCache();

        return $this->sendResponse(null,trans('admin.success'));
    }

    public function export($type,Request $request)
    {
        try
        {
            $request->merge(['page' => 1,'per_page' => 99999]);
            switch ($type) {
                case 'xlsx':
                case 'xls':
                    return $this->exportXls($request);
                    break;
            }
        }
        catch (\Exception $e)
        {
            return $this->sendError($e->getMessage());
        }
    }

    public function exportXls(Request $request)
    {
        return $this->_exportXls();
    }

    protected function _exportXls($data=[],$header = [],$format = [],$name='export')
    {
        $_name = $name . '_'. Carbon::now()->format('Ymd') . '.xlsx';
        return Excel::download(new GeneralExport($data,$header,$format),$_name);
    }

    protected function _destroy($model)
    {
        if (empty($model)) {
            throw new Exception(trans('admin.not_found'), 1);
        }

        $model->delete();
    }

    protected function clearCache()
    {
        if (count($this->cacheKeys) > 0)
        {
            foreach ($this->cacheKeys as $key)
            {
                CacheHelper:: clearKeys($key);
            }
        }
        return true;
    }


}
