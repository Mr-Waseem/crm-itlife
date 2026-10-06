<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RightsLevel3 extends Model
{
    use HasFactory;
    protected $table = "rights_level3";
    protected $fillable = array('code', 'title', 'url', 'right_level2_id');

    public function right_level2()
    {
        return $this->belongsTo(RightsLevel2::class, 'right_level2_id');
    }
}