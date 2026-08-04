<div class="form-group">
	<label class="form-control-label">{{ $label }} @include('cms.components.inputs.asterix')</label>
	<input type="number" step="any" name="{{ $name }}" class="form-control form-control-alternative {{ (isset($class) && $class ? $class : '') }}" value="{{ (isset($row->$name) && $row->$name) ? old($name, $row->$name) : old($name) }}" @if(isset($readonly) && $readonly) readonly @endif>

	@if(isset($text))
	<small><em>{{ $text }}</em></small>
	@endif
	
	@include('cms.components.inputs.error')
</div>