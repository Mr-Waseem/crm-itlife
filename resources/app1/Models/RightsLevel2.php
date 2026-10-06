<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RightsLevel2 extends Model
{
    use HasFactory;
    protected $table = "rights_level2";
    protected $fillable = array('code', 'title', 'url', 'right_level1_id', 'status');

    public function right_level1()
    {
        return $this->belongsTo(RightsLevel1::class, 'right_level1_id');
    }

    public function right_level3()
    {
        return $this->hasMany(RightsLevel3::class, 'right_level2_id');
    }
}