<?php

namespace App\Models;

//use App\Models\Role;
use Hash;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Uuid;

class ProblemTopic extends BaseModel{
    use Uuid;
    use SoftDeletes;

    /**
     * @var int Auto increments integer key
     */
    protected $table = 'tbl_problem_topics';
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
        'name'
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string'
    ];
    
    public function list()
    {
        return $this->hasMany('App\Models\ProblemList','id','topic_id');
    }
}
