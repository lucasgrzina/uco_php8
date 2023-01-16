<?php

namespace App;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Role as SpatieRole;
use Yajra\Auditable\AuditableTrait;

/**
 * Class Role
 * @package App
 * @version November 13, 2017, 5:53 pm UTC
 *
 * @property string name
 */
class Role extends SpatieRole
{
    use AuditableTrait;
    use SoftDeletes;

    public $hidden = ['pivot'];

    public static $rules  = [
        'name' => 'required'
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleted(function ($model) 
        {
            $model->name = $model->id . '_' . $model->name;
            $model->save();
        });        
    }    
}
