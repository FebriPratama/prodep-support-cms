<?php

namespace App\Models;

use Watson\Rememberable\Rememberable;
use App\Uuid;

class Faq extends BaseModel{
    use Uuid;
    use Rememberable;

    /**
     * @var int Auto increments integer key
     */
    protected $table = 'tbl_faqs';
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
        'name', 'description','slug'
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

    //public $rememberCacheTag = 'faq_queries';
    public $rememberFor = 60;

}
