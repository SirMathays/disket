<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chart extends Model
{
	protected $casts = [
        'lanes' => 'json'
    ];

    public function players()
    {
    	return $this->belongsToMany(Player::class)->withPivot('stats');
    }

    public function calculatePlayerTotalScore($stats)
    {
    	$lanes = $this->lanes;
    	$stats = json_decode(json_encode($stats), true);

		$totalScore = 0;
    	if($stats) {
			foreach($lanes as $index => $par) {
				if(array_key_exists($index, $stats)) {
					$calculation = $stats[$index] - $par;
					$totalScore = $totalScore + $calculation;
				}
			}
    	}

    	return $totalScore;
    }
}
