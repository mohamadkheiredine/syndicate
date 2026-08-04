<div class="form-group">
	<label class="form-control-label">{{ $label }}</label>
	<div>
		<label class="custom-toggle mb-0">
			<input name="{{ $name }}" type="checkbox" value="{{ $value }}" @if(isset($row) && $row->$name == $value || old($name) == $value) checked @endif>
			<span class="custom-toggle-slider rounded-circle"></span>
		</label>
	</div>

	@if(isset($text))
	<small><em>{{ $text }}</em></small>
	@endif
</div>
