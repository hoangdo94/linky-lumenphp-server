<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
     protected $table = 'like';
     public $timestamps = false;
     protected $fillable = ['user_id', 'post_id'];

}
