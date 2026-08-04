@extends('errors.main')

@section('page_title', '403 | Forbidden')

@section('content')

<div class="container">
	<h1>403</h1>
	<p>User does not have the right permissions.</p>
	<small class="text-muted"><a href="{{ url()->previous() }}">Go Back</a></small>
</div>

@endsection