<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['user_id','title','sub_title','details','photo','bg_color'];
    public $timestamps = false;
}
