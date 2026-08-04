@if(session($with))
<div class="alert alert-{{ $bg }} alert-dismissible {{ isset($class) && $class ? $class : '' }}" role="alert">
	{{ session($with) }}
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	</button>
</div>
@endif