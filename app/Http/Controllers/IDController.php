<?php

namespace App\Http\Controllers;

use App\Helpers\CacheHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class IDController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function clear($type) {
        try
        {
            \Artisan::call($type.':clear');
            return "OK";
        }
        catch(\Exception $ex)
        {
            return $ex->getMessage();
        }
    }

    public function sendEmail() {
        try
        {
            Mail::raw('Ya estás participando', function($message)
            {
                $message->from('tienda@luigibosca.com', 'Felicitaciones');

                $message->to('lucasgrzina@gmail.com');

            });
            
        }
        catch(\Exception $ex)
        {
            return $ex->getMessage();
        }        
    }
    
}