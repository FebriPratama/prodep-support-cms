<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission as SpatiePermission;
use App\Uuid;

class Permission extends SpatiePermission
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