<?php

namespace App\Models\API;

use Specialtactics\L5Api\Models\RestfulModel;
use App\Uuid;

class ThreadApi extends RestfulModel
{
    use Uuid;
    
    protected $table = 'tbl_threads';

    /**
     * @var int Auto increments integer key
     */
    protected $keyType = "string";
    public $incrementing = false;
    protected $guard_name = 'api';
    public $primaryKey = 'id';

    protected $casts = [
        'id' => 'string'
    ];

    protected $fillable = [
        'so_id', 'pl_id','cf_id','customer_id','cs_id','thread_status','thread_reason'
    ];

    public function salesorder()
    {
        return $this->hasOne('App\Models\API\SalesOrderApi','id','so_id');
    }

    public function problem()
    {
        return $this->hasOne('App\Models\API\ProblemListApi','id','pl_id');
    }

    public function form()
    {
        return $this->hasOne('App\Models\API\ComplainFormApi','id','cf_id');
    }

    public function customer()
    {
        return $this->hasOne('App\Models\User','user_id','customer_id');
    }

    public function support()
    {
        return $this->hasOne('App\Models\User','user_id','cs_id');
    }

    public function messages()
    {
        return $this->hasMany('App\Models\Message','id','thread_id');
    }

}
