<?php

namespace App;

use Eloquent as Model;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    public $hidden = ['pivot'];
}