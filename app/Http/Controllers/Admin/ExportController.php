<?php

namespace App\Http\Controllers\Admin;

use App\Exports\GeneralExport;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
//use Maatwebsite\Excel\Excel;

class ExportController extends Controller
{
	private $excel = null;
    public function __construct(Excel $excel)
    {
    	$this->excel = $excel;
    }

    public function export(UserRepository $user_repo)
    {
        $data = $user_repo->with([
            'roles' => function($q) {
                $q->select('name','id');
            }
        ])->all()->toArray();
        //return response()->json($data);


    	//$data = [['Lucas','Grzina'],['Fede','Roife']];
    	$header = [
            'nombre' => 'Nombre',
            'apellido' => 'Apellido',
            'roles' => 'Rol',
            'fecha_nac' => 'Fecha Nac'
        ];
    	$format = [
            'roles' => function($value) {
                return $value[0]['name'];
            },
            'fecha_nac' => function ($value) {
                return ($value ? Carbon::createFromFormat('Y-m-d',$value)->format('d/m/Y') : $value);
            }
        ];
    	return $this->excel->download(new GeneralExport($data,$header,$format),'general.xlsx');
    }

    public function exportar($type,UserRepository $user_repo)
    {
        switch ($type) {
            case 'usuarios':
                $data = $user_repo->all()->toArray();
                //return respo
                break;

            default:
                # code...
                break;
        }
    }

}
