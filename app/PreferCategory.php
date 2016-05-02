<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class PreferCategory extends Model
{
     protected $table = 'prefer_category';
     public $timestamps = false;
     protected $fillable = ['user_id', 'cate_id'];

}
