<?php

namespace App\Http\Controllers\Admin;


use Carbon\Carbon;
use App\Configuraciones;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AppBaseController;


class DashboardController extends AppBaseController
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $data = [];
        return view('admin.dashboard',['data' => $data]);
    }

 
  

    
    public function unauthorized()
    {
        return view('admin.unauthorized');
    }    

    
}
