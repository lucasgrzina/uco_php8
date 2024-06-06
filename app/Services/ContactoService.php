<?php
namespace App\Services;


use App\Repositories\ContactosRepository;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\Admin\CUContactosRequest;
use Illuminate\Support\Facades\Mail;

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
            $this->enviarEmail($salida);
            return $this->sendResponse([],'');
        } catch (\Exception $e) {
            // logger($e);
            return $this->sendError($e->getMessage(),$e->getCode());
        }
    }

    protected function enviarEmail ($model) {
        try
        {
            $html = "
                <strong>Nombre:</strong> {$model->nombre}<br>
                <strong>Apellido:</strong> {$model->apellido}<br>
                <strong>Email:</strong> {$model->email}<br>
                <strong>Pais:</strong> {$model->pais}<br>
                <strong>Teléfono:</strong> {$model->tel_prefijo} {$model->tel_numero}<br>
                <strong>Mensaje:</strong> {$model->mensaje}<br>
            ";

            /*Mail::raw($html, function($message)
            {
                //$message->from('tienda@luigibosca.com', 'Felicitaciones');

                $message->to('lucasgrzina@gmail.com');

            });*/

            \Mail::send([], [], function (Message $message) use ($html) {
                $message->to('lucasgrzina@gmail.com')
                ->subject('Nuevo contacto')
                //->from('my@email.com')
                ->setBody($html, 'text/html');
            });

        }
        catch(\Exception $ex)
        {
            logger($ex->getMessage());
            // return $ex->getMessage();
        }
    }
}
