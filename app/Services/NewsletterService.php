<?php
namespace App\Services;


use App\Http\Controllers\AppBaseController;
use App\Repositories\NewslettersRepository;
use App\Http\Requests\Admin\CUNewslettersRequest;

class NewsletterService extends AppBaseController
{
    protected $repository;
    public function __construct(NewslettersRepository $repo)
    {
        $this->repository = $repo;
    }

    public function guardar(CUNewslettersRequest $request) {
        try {
            if ($this->repository->findByField('email',$request->email)->count() < 1) {
                $salida = $this->repository->create($request->only('email'));
            } else {
                throw new \Exception(trans('front.modulos.suscripcion.gracias'), 500);
            }

            return $this->sendResponse(['message' => trans('front.modulos.suscripcion.gracias')],'');
        } catch (\Exception $e) {
            logger($e);
            return $this->sendError($e->getMessage(),$e->getCode());
        }
    }
}
