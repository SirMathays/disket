@extends('layouts.app')

@section('content')
	<fairway-main 
		:data="{{ $chart->toJson() }}"
		@isset($player) :player="{{ $player->toJson() }}" @endisset
		:lane="'{{ Request::input('l') }}'"></fairway-main>
@endsection