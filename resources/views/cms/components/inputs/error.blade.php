@if($errors->has($name))
<span class="invalid-feedback text-danger d-block" role="alert">
	<strong>{{ $errors->first($name) }}</strong>
</span>
@endif