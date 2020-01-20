<?php

namespace App\Models\API;

use Specialtactics\L5Api\Models\RestfulModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Uuid;

class ThreadApi extends RestfulModel
{
    use SoftDeletes,Uuid;
    
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
        return $this->hasOne('App\Models\API\SalesOrderApi','so_id','id');
    }

    public function problem()
    {
        return $this->hasOne('App\Models\API\ProblemListApi','pl_id','id');
    }

    public function form()
    {
        return $this->hasOne('App\Models\API\ProblemListApi','cf_id','id');
    }

    public function customer()
    {
        return $this->hasOne('App\Models\User','customer_id','id');
    }

    public function support()
    {
        return $this->hasOne('App\Models\User','cs_id','id');
    }

}
