<?php
//app/Helpers/Envato/User.php
namespace App\Helpers;
 
class AdminHelper {

    public static function contentHeader($title,$sub = '',$btnType=false,$btnClick='') 
    {
        $header = "
        <section class=\"content-header\">
            <h1>
            {$title}
            </h1>
            <small>{$sub}</small>";

        if ($btnType)
        {
            $header.= "<div class=\"pull-right\"><button-type type=\"{$btnType}\" @click=\"{$btnClick}\"></button-type></div>"; 
        }

        $header.= "</section>";

        return $header;
    }
    
    public static function mostrarMenu($actionPerms) 
    {
        if (auth()->user()->hasRole('Superadmin'))
        {
            return true;
        }
        
        if (!is_array($actionPerms))
        {
            $actionPerms = [$actionPerms];
        }

        foreach ($actionPerms as $value) 
        {
            if ( auth()->user()->can('editar-'.$value) || auth()->user()->can('ver-'.$value) )
            {
                return true;
            }
        }

        return false;
    }
}