@php
if(isset($value) && $value){
	$component_value = old($name, $value);
} elseif(isset($row->$name) && $row->$name){
	$component_value = old($name, $row->$name);
} else {
	$component_value = old($name);
}
@endphp

<div class="form-group">
	<label class="form-control-label d-block">{{ $label }} @include('cms.components.inputs.asterix')</label>

	{{-- Old Image Preview --}}
	@if($component_value)
	<img width="200" class="img-thumbnail mb-3" src="{{ $component_value }}">
	@endif

	{{-- New Image Preview --}}
	<img width="200" class="img-thumbnail align-top image-preview-js d-none mb-3" src="">

	{{-- Display remove button if the image is not required --}}
	@if( isset($asterix) && $asterix != true && isset($row) && $row->$name )
	<div class="row">
		<div class="col-md mb-3 mb-md-0">
			@endif
			<input name="{{ $name }}" type="file" class="form-control form-control-alternative file-input-js">
			@if( isset($asterix) && $asterix != true && isset($row) && $row->$name )
		</div>
		<div class="col-md-auto">
			<a href="#" class="btn btn btn-danger" onclick="if(confirm('Please save your work before deleting this image!')) window.location.href='{{ route('admin.'.$page_info['link'].'.image.remove', $row->id) }}';">Remove</a>
		</div>
	</div>
	@endif

	@if(isset($text))
	<small><em>{{ $text }}</em></small>
	@endif

	@include('cms.components.inputs.error')
</div>