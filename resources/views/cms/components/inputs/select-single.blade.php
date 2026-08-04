<div class="form-group">
	<label class="form-control-label">{{ $label }} @include('cms.components.inputs.asterix')</label>
	<select class="select2-custom form-control" name="{{ $name }}">
		<option {{ (isset($row->$name) && $row->$name) ? '' : 'selected' }} disabled>{{ $placeholder }}</option>
		@if(!isset($asterix))
		<option value="0">None</option>
		@endif
		@foreach($rows as $select_row)
		<option value="{{ $select_row[$value_attribute] }}" @if(old($name) == $select_row[$value_attribute] || isset($row->$name) && $row->$name == $select_row[$value_attribute]) selected @endif>{{ $select_row[$attribute] }}</option>
		@endforeach
	</select>

	@include('cms.components.inputs.error')
</div>