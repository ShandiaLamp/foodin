<?php

namespace Shandialamp\Foodin\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'admin_menus';

     protected $fillable = [
        'scope', 'name', 'path', 'component', 'meta', 'routes',
    ];

    protected $casts = [
        'meta'  => 'json',
        'routes'    => 'json',    
        'disabled'  => 'bool',
    ];
}
