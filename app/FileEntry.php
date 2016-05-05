<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class FileEntry extends Model
{
     protected $table = 'file_entry';
     protected $fillable = ['filename', 'mime'];

}
