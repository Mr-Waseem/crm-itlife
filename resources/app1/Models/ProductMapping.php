<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductMapping extends Model
{
    use HasFactory;
    protected $fillable = ['warehouse_id', 'product_warehouse_id', 'created_by'];
}
