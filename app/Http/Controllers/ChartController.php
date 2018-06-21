<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Chart;
use App\Player;
use App\ChartPlayer;

use DB;

class ChartController extends Controller
{
    public function start()
    {
        return view('start');
    }

	public function new()
	{
    	return view('new');
    }

    public function create(Request $request)
    {
    	$data = $request->input();

    	$lanes = [];
    	foreach($data['lanes'] as $index => $lane) {
    		$laneNumber = $index+1;
    		$lanes[$laneNumber] = $lane['par'];
    	}

        $chart = null;
        $player = null;

        DB::transaction(function () use ($data, $lanes, &$chart, &$player) {
        	$player = new Player;
        	$player->name = $data['username'];
        	$player->save();

        	$chart = new Chart;
        	$chart->name = $data['name'];
        	$chart->lanes = $lanes;
        	$chart->secret_key = str_random(15);
        	$chart->save();

        	$chart->players()->attach($player->id);
        });

        if($chart != null && $player != null) {
        	return response([
                'redirect' => route('chart.show', [$chart->secret_key, $player->id]),
            ], 200);
        }

        return response([
            'message' => 'Something went wrong'
        ], 500);
    }

    public function show($secretKey, $playerId = null, Request $request)
    {
        // find the chart
        $chart = Chart::where('secret_key', $secretKey)->with('players')->firstOrFail();

        // jsonify player stats
        $players = $chart->players;
        foreach($players as &$row) {
            $row->stats = json_decode($row->pivot->stats);
            $row->total_score = $chart->calculatePlayerTotalScore($row->stats);
        }
        $chart->players_formatted = $players->sortBy('total_score')->values();


        // if player is selected
        $player = null;
        if($playerId) {
            $player = $chart->players()->where('players.id', $playerId)->firstOrFail();
            $player->stats = json_decode($player->pivot->stats);
            $player->total_score = $chart->calculatePlayerTotalScore($player->stats);
        }

        // check the lane
        if($request->input('l') && !array_key_exists($request->input('l'), $chart->lanes)) {
            abort(404);
        }

        // return teh view
        return view('show', [
            'chart' => $chart,
            'player' => $player,
        ]);
    }

    public function join(Request $request) 
    {
        $data = $request->input();

        $chart = Chart::find($data['chart']);

        $player = new Player;
        $player->name = $data['username'];
        $player->save();

        $chart->players()->attach($player->id);

        return response([
            'message' => 'User added',
            'redirect' => route('chart.show', [$chart->secret_key, $player->id]),
        ], 200);
    }

    public function saveLane(Request $request) 
    {
        $data = $request->input();

        $row = ChartPlayer::where('player_id', $data['player'])->where('chart_id', $data['chart'])->first();

        $stats = $row->stats;
        $stats[$data['lane']] = $data['score'];
        $row->stats = $stats;
        $row->save();

        return response([
            'message' => 'Score saved'
        ], 200);
    }
}
