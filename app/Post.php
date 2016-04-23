<?php namespace App;
  
use Illuminate\Database\Eloquent\Model;
  
class Post extends Model
{
     protected $table = 'post';
     public $timestamps = false;
     protected $fillable = ['user_id', 'cate_id', 'type_id', 'num_likes', 'link', 'content'];
     
}
?>