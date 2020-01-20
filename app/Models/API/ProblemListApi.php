<?php

namespace App\Models\API;

use Specialtactics\L5Api\Models\RestfulModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Uuid;

class ProblemListApi extends RestfulModel
{
    use SoftDeletes,Uuid;
    
    protected $table = 'tbl_problem_lists';

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

    public function topic()
    {
        return $this->belongsTo('App\Models\API\ProblemTopicApi','topic_id','id');
    }

}
