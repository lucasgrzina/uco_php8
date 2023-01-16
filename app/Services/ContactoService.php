<?php
namespace App\Services;


use App\Repositories\ContactosRepository;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\Admin\CUContactosRequest;

class ContactoService extends AppBaseController 
{
    protected $repository;
    public function __construct(ContactosRepository $repo)
    {
        $this->repository = $repo;
    }    

    public function guardar(CUContactosRequest $request) {
        try {
            $salida = $this->repository->create($request->all());
            return $this->sendResponse([],'');
        } catch (\Exception $e) {
            // logger($e);
            return $this->sendError($e->getMessage(),$e->getCode());    
        }
    }
}
