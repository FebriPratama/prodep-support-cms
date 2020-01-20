<?php

namespace App\Models;

//use App\Models\Role;
use Hash;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Uuid;

class ProblemList extends BaseModel{
    use Uuid;
    use SoftDeletes;

    /**
     * @var int Auto increments integer key
     */
    protected $table = 'tbl_problem_lists';
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
        'topic_id', 'title','description'
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string'
    ];

    public function topic()
    {
        return $this->belongsTo('App\Models\ProblemTopic','topic_id','id');
    }
}
