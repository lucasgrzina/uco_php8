<?php

namespace App\Http\Controllers;


use App\Helpers\StorageHelper;

use App\Services\ApiRolService;
use App\Services\ApiSmsService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class TestController extends AppBaseController
{
    public function doppler() {

        $listId = 28395666;
        $response = dopplerSuscribirALista("lucasgrzina+50@gmail.com","Lucas",$listId);
        return response()->json(($response));


    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function sms(ApiSmsService $smsService) {
        try
        {
            return $smsService->enviarCodigo('11','34290838');
            
        }
        catch(\Exception $ex)
        {
            \Log::info($ex->getMessage());
        }
    }
    public function rolLogin(ApiRolService $rolService) {
        try
        {
            
            $respuesta = $rolService->login();
            return $this->sendResponse($respuesta, trans('admin.success'));
            
        }
        catch(\Exception $ex)
        {
            \Log::info($ex->getMessage());
        }
    }
    public function rolConsultarInforme(ApiRolService $rolService) {
        try
        {
            $respuesta = $rolService->consultarInforme('23300075169');
            return $this->sendResponse($respuesta, trans('admin.success'));            
        }
        catch(\Exception $ex)
        {
            \Log::info($ex->getMessage());
        }
    }    
    public function rolSolicitarInforme(ApiRolService $rolService) {
        try
        {
            $respuesta = $rolService->solicitarInforme('23300075169');
            return $this->sendResponse($respuesta, trans('admin.success'));            
        }
        catch(\Exception $ex)
        {
            \Log::info($ex->getMessage());
        }
    }     

    public function leerS3() {
        
        $path = env('AMAZON_S3_FOLDER').'/tmp/test2.png';
        $img = StorageHelper::contenido('tmp/1605205406-Premio-01.jpg','uploads_local');

        StorageHelper::put(
            $path,
            $img
        );

        //\Log::info(StorageHelper::url(env('AMAZON_S3_FOLDER').'/tmp/1612980492-2021-01-18-16-32-localhost.png','uploads'));
        $files = StorageHelper::archivos(env('AMAZON_S3_FOLDER').'/tmp');
        return $files;
    }
}