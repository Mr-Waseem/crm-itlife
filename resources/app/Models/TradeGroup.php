<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TradeGroup extends Model
{
    use HasFactory;
    protected $fillable = array('name');
}