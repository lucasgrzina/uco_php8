<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ErrorController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index ($code = 404) 
    {
      $data = [
          'error' => [
              'message' => ''
          ]
      ]; 

      switch ($code) {
          case 403:
              $data['error']['message'] = 'No estás autorizado a acceder a esta sección.';
              break;
          case 404:
              $data['error']['message'] = 'La página solicitada no existe.';
              break;
      }
      
      return view('admin.errors',$data);
    } 
}
