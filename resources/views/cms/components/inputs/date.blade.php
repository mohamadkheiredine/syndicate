<div class="form-group">
	<label class="form-control-label">{{ $label }} @include('cms.components.inputs.asterix')</label>
	<input name="{{ $name }}" class="form-control form-control-alternative datepicker" value="{{ (isset($row->$name) && $row->$name) ? old($name, date('d/m/Y', strtotime($row->$name))) : old($name) }}">

	@if(isset($text))
	<small><em>{{ $text }}</em></small>
	@endif
	
	@include('cms.components.inputs.error')
</div>