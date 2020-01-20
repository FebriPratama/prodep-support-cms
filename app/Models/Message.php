<?php

namespace App\Models;

use App\Uuid;

class Message extends BaseModel{
    use Uuid;

    /**
     * @var int Auto increments integer key
     */
    protected $table = 'tbl_messages';
    protected $keyType = "string";
    public $incrementing = false;
    protected $guard_name = 'web';
    public $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sender_id', 'type','body'
    ];

    protected $hidden = [
        'id'
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string'
    ];

}
