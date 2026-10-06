<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RightsLevel1 extends Model
{
    use HasFactory;
    protected $table = "rights_level1";
    protected $fillable = array('code', 'title', 'url', 'status');

    public function right_level2()
    {
        return $this->hasMany(RightsLevel2::class, 'right_level1_id');
    }
}