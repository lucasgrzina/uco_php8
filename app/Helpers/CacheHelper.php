<?php
//app/Helpers/Envato/User.php
namespace App\Helpers;
 
class CacheHelper {

    public static function getKeys()
    {
        if(\Cache::has('cacheKeys')) {
            return \Cache::get('cacheKeys');
        } 

        return [];
    }

    public static function cacheKeys($key) 
    {
        $keys = self::getKeys();
        array_push($keys, $key); 
        \Cache::forever('cacheKeys', array_unique($keys));
        
        return true;
    }

    public static function clearKeys($wildcard) {
        $keys = self::getKeys();

        if(\is_array($keys)){
            $filtered = array_where($keys, function ($value, $key) use ($wildcard) {
                return strpos($value, $wildcard."-") > -1 ? true : false;
            });
            foreach($filtered as $filter) {
                \Cache::forget($filter);
            }
        }    
    }
}