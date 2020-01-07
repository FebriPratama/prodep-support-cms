<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as SpatieRole;
use App\Uuid;

class Role extends SpatieRole
{
    use Uuid;
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = "string";

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string'
    ];
}