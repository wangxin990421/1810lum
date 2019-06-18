<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AdminModel extends Model
{
    protected $table = 'admin';
    protected $primaryKey='a_id';
    public $timestamps = false;
}