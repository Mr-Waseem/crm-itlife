<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhoneBook extends Model
{
    protected $fillable = ['date', 'name', 'phone', 'email', 'type', 'address'];
}
