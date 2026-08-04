@extends('errors.main')

@section('page_title', '500 | Server Error')

@section('content')

<div class="container">
	<h1>500</h1>
	<p>Server Error.</p>
	<small class="text-muted"><a href="{{ url()->previous() }}">Go Back</a></small>
</div>

@endsection