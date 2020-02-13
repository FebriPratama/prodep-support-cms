<?php

namespace App\Models\API;

use Specialtactics\L5Api\Models\RestfulModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Uuid;

class ComplainFormApi extends RestfulModel
{
    use SoftDeletes,Uuid;
    
    protected $table = 'tbl_complaint_forms';

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
        'customer_name', 'customer_email','sales_order_no','problem_desc'
    ];

    public function thread()
    {
        return $this->hasOne('App\Models\API\ThreadApi','cf_id','id');
    }

}
