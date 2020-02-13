<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use App\Uuid;

class Message extends BaseModel implements HasMedia{
    use Uuid,HasMediaTrait;

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
        'sender_id', 'type','body','thread_id'
    ];

    protected $appends = [
        'media_url'
    ];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string'
    ];

    public function images()
    {
        return $this->hasMany('Spatie\MediaLibrary\Models\Media','model_id','id');
    }

    public function getMediaUrlAttribute()
    {
        foreach($this->images as $img){
            return asset('storage/' . $img->id .'/'. $img->file_name);
        }
    }

}
