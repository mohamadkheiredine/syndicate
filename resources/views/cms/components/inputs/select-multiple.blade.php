@php
	if(isset($multi_attributes) && $multi_attributes){
		$multi_attributes = explode(' - ', $multi_attributes);
	}
@endphp

<div class="form-group">
	<label class="form-control-label">{{ $label }} @include('cms.components.inputs.asterix')</label>
	<select class="select2-custom form-control form-custom" name="{{ $name }}[]" multiple="" @if(isset($asterix) && $asterix) required @endif>
		@foreach($rows as $select_row)
		<option value="{{ $select_row[$value_attribute] }}" @if(isset($row->$name) && empty(old($name))) @foreach($row->$name as $single) @if($single->pivot->$pivot == $select_row['id']) selected @endif @endforeach @endif @if(collect(old($name))->contains($select_row['id'])) selected @endif>@if(isset($attribute) && $attribute) {{ $select_row[$attribute] }} @else @foreach($multi_attributes as $key => $multi_attribute) {{ $select_row[$multi_attribute] }} @if($key != count($multi_attributes) - 1)-@endif @endforeach @endif</option>
		@endforeach
	</select>

	@include('cms.components.inputs.error')
</div>