<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Meta extends Model
{
     protected $table = 'meta';
     protected $fillable = ['link', 'thumb_id', 'title', 'description'];
}
