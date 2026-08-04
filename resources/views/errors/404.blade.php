@extends('errors.main')

@section('page_title', '404 | Page Not Found')

@section('content')

<div class="container">
	<h1>404</h1>
	<p>The link you clicked may be broken or the <br /> page may have been removed.</p>
	<small class="text-muted"><a href="{{ url()->previous() }}">Go Back</a></small>
</div>

@endsection