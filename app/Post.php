<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
     protected $table = 'post';
     protected $fillable = ['user_id', 'cate_id', 'type_id', 'link', 'content', 'meta_id'];

}
