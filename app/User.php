<?php

namespace App;


use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Yajra\Auditable\AuditableTrait;
use App\Notifications\ResetPassword;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    use AuditableTrait;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $guard = 'admin';
    protected $fillable = [
        'nombre','apellido','enabled', 'email', 'password'
    ];
    protected $dates = ['deleted_at'];

    protected $casts = [
        'enabled' => 'boolean',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public $appends = ['nombre_completo'];

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = \Hash::make($password);
    }  
        
    public function getNombreCompletoAttribute()
    {
        return $this->attributes['nombre'] . ' ' . $this->attributes['apellido'];
    }      

    public function roles()
    {
        return $this->morphToMany('App\Role', 'model', 'model_has_roles');
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    

    protected static function boot()
    {
        parent::boot();

        /*static::saving(function ($model)
        {
            $model->name = $model->first_name . ' ' . $model->last_name;
        });**/

        static::deleted(function ($model) 
        {
            $model->email = $model->id . '_' . $model->email;
            $model->save();
        });        
    }
  
}
