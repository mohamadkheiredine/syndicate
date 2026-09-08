@extends('cms.layouts.main')

@section('content')
@include('cms.layouts.headers.partials', ['title' => 'Edit Activity'])

<div class="container-fluid mt--7">
    <div class="card bg-secondary shadow">
        <div class="card-header bg-white border-0">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="mb-0">{{ $page_info['title'] }}</h3>
                </div>
                <div class="col-auto text-right">
                    <a href="{{ route('admin.'.$page_info['link'].'.index') }}" class="btn btn-sm btn-primary">Back to list</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            @include('cms.components.alert', ['with' => 'status', 'bg' => 'success'])

            <form id="activity-edit-form" method="post" action="{{ route('admin.'.$page_info['link'].'.update', $row) }}" enctype="multipart/form-data" autocomplete="off">
                @csrf
                @method('put')

                {{-- Title --}}
                @include('cms.components.inputs.text', ['label' => 'Name/Title', 'asterix' => true, 'name' => 'title', 'maxlength' => 255, 'row' => $row])

                {{-- Date --}}
                @include('cms.components.inputs.date', ['label' => 'Date', 'name' => 'post_date', 'row' => $row])

                {{-- Place --}}
                @include('cms.components.inputs.text', ['label' => 'Place', 'name' => 'place', 'maxlength' => 255, 'row' => $row])

                {{-- Main Image --}}
                @include('cms.components.inputs.image', ['label' => 'Main Image', 'name' => 'main_image', 'value' => $row->main_image])

                <div class="form-group">
                    <label class="form-control-label d-block">Other Images (Gallery)</label>

                    @if($row->gallery->count())
                    <div class="row mb-3" id="gallery-images-wrapper">
                        @foreach($row->gallery as $galleryRow)
                        <div class="col-auto mb-2" id="gallery-image-{{ $galleryRow->gallery_id }}">
                            <img src="{{ $galleryRow->gallery_image }}" width="100" class="img-thumbnail d-block mb-1">
                            <button type="button" class="btn btn-sm btn-danger btn-block" onclick="markGalleryImageForRemoval({{ $galleryRow->gallery_id }})">Remove</button>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    <input name="gallery_images[]" type="file" multiple class="form-control form-control-alternative">
                    <small><em>Choose more files here to add extra images to the gallery above.</em></small>
                </div>

                <div class="form-group">
                    <label class="form-control-label d-block">Upload Any File (PDF/Word)</label>
                    @if($row->any_file)
                    <p><a href="{{ $row->any_file }}" target="_blank">Current file</a></p>
                    @endif
                    <input name="any_file" type="file" class="form-control form-control-alternative">
                    @include('cms.components.inputs.error', ['name' => 'any_file'])
                </div>

                {{-- Short Description --}}
                @include('cms.components.inputs.textarea', ['label' => 'Short Description', 'asterix' => true, 'name' => 'short_description', 'row' => $row])

                {{-- Description --}}
                @include('cms.components.inputs.textarea', ['label' => 'Description', 'name' => 'description', 'quill' => true, 'row' => $row])

                @can('syndicate_activities-publish')
                {{-- Published --}}
                @include('cms.components.inputs.checkbox', ['label' => 'Published', 'name' => 'publish_status', 'value' => 1, 'row' => $row, 'text' => 'Leave this off to send it back to pending instead.'])
                @else
                <div class="form-group">
                    <label class="form-control-label d-block">Currently</label>
                    <span class="badge badge-pill {{ (int) $row->publish_status === 1 ? 'badge-success' : 'badge-warning' }}">
                        {{ (int) $row->publish_status === 1 ? 'Published' : 'Pending' }}
                    </span>
                </div>
                <small><em>Saving this edit will send it back to pending, awaiting approval.</em></small>
                @endcan

                <div class="text-center">
                    <button type="submit" class="btn btn-success mt-4">Save</button>
                </div>
            </form>
        </div>
    </div>

    @include('cms.layouts.footers.auth')
</div>
@endsection

@push('script')
<script type="text/javascript">
    // Just hides the image and stages its id for removal - nothing is
    // actually deleted until the whole form is saved.
    function markGalleryImageForRemoval(galleryId) {
        document.getElementById('gallery-image-' + galleryId).remove();

        var hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'remove_gallery_images[]';
        hiddenInput.value = galleryId;
        document.getElementById('activity-edit-form').appendChild(hiddenInput);
    }
</script>
@endpush
