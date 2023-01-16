<?php
namespace App\Helpers;

class FrontHelper {

    public static function formattedDate($date,$type="news") 
    {
        $format_en = "";
        $format_other = "";

        switch ($type) {
            case 'datepicker':
                $format_en = 'Y-m-d';
                $format_other = 'd/m/Y';
                break;
            default:
                $format_en = 'l, F d | Y';
                $format_other = 'l d, F | Y';            
                break;
        }

        //example
        if(config('app.locale') == 'en')
        {
            return \Date::parse($date)->format($format_en);
        }
        else
        {
            return ucwords(\Date::parse($date)->format($format_other));
        }
    }

    public static function getCookieRegistrado($prefijo = '') {
        return \Cookie::get($prefijo.config('constantes.cookieRegistrado'),null);
    }

    public static function setCookieRegistrado($valor,$prefijo = '') {
        return \Cookie::queue(\Cookie::make($prefijo.config('constantes.cookieRegistrado'), md5($valor), 518400));
    }
 
    public static function route($name,$params = []) {
        $codigoPais = request()->segment(1);
        $params = array_unshift($params,$codigoPais);
        return route($name,$params);
    }
}