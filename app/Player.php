<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    public function charts()
    {
    	return $this->belongsToMany(Chart::class);
    }
}
