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

    public function guardar($lang,CUNewslettersRequest $request) {
        try {
            $model = $this->repository->findByField('email',$request->email)->first();
            if (!$model) {
                $salida = $this->repository->create($request->only(['email','nombre','apellido','recibir_info']));
            }else {
                $model->fill($request->only(['email','nombre','apellido','recibir_info']));
                $model->save();
                //throw new \Exception(trans('front.modulos.suscripcion.gracias'), 501);
            }

            return $this->sendResponse(['message' => trans('front.modulos.suscripcion.gracias')],'');
        } catch (\Exception $e) {
            logger($e);
            return $this->sendError($e->getMessage(),500);
        }
    }
}
