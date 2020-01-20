<?php

namespace App\Models\API;

use Specialtactics\L5Api\Models\RestfulModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Uuid;

class SalesOrderApi extends RestfulModel
{
    use SoftDeletes,Uuid;
    
    protected $table = 'tbl_salesorders';

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

    protected $hidden = [
        'user_id'
    ];

    protected $appends = [
        'so_total_rp'
    ];

    /*
     * Add your own base customisation here
     */

    protected $fillable = [
        'so_type', 'so_product_name','so_total','user_id','so_status'
    ];

    public function getSoTotalRpAttribute()
    {
        return "Rp " . number_format($this->so_total,2,',','.');
    }

}
