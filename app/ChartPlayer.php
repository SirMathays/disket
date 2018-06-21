<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChartPlayer extends Model
{
	protected $table = 'chart_player';
	
	public $timestamps = false;
	
    protected $casts = [
        'stats' => 'json'
    ];
}
