<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
     protected $table = 'comment';
     protected $fillable = ['user_id', 'post_id', 'content'];

}
