<?php

namespace App\Models\API;

use Specialtactics\L5Api\Models\RestfulModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Uuid;

class ProblemTopicApi extends RestfulModel
{
    use SoftDeletes,Uuid;
    
    protected $table = 'tbl_problem_topics';

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

    public function list()
    {
        return $this->hasMany('App\Models\API\ProblemListApi','topic_id','id');
    }

}
