<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuRights extends Model
{
    use HasFactory;
    protected $fillable = array('user_id', 'level_id', 'type');
    // protected $casts = array(
    //     'level1_id' => 'array',
    //     'level2_id' => 'array',
    //     'level3_id' => 'array'
    // );
}