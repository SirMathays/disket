@extends('layouts.app')

@section('style', 'splash')

@section('content')
	<div class="card">
		<div class="card-header">
			<a class="btn btn-lg btn-primary btn-block" href="/uusi">Luo uusi</a>
		</div>
		<div class="card-body py-5">
			<search-block></search-block>
		</div>
	</div>
@endsection