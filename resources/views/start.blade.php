@extends('layouts.app')

@section('content')
	<div class="card">
		<div class="card-body">
			<div class="form-group mb-5 mt-4">
				<a class="btn btn-lg btn-primary btn-block" href="/uusi">Luo uusi</a>
			</div>
			<hr class="my-5">
			<search-block></search-block>
		</div>
	</div>
@endsection