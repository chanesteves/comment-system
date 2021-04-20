<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['comment', 'reply_id', 'page_id', 'user_name'];
   	protected $dates = ['created_at', 'updated_at'];
 
  	public function replies()
   	{
       return $this->hasMany('App\Comment', 'id', 'reply_id');
   	}
}
