@extends('cms.layouts.main')

@section('content')
@include('cms.layouts.headers.partials', ['title' => 'Add Activity'])

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
            <form method="post" action="{{ route('admin.'.$page_info['link'].'.store') }}" enctype="multipart/form-data" autocomplete="off">
                @csrf

                {{-- Title --}}
                @include('cms.components.inputs.text', ['label' => 'Name/Title', 'asterix' => true, 'name' => 'title', 'maxlength' => 255])

                {{-- Date --}}
                @include('cms.components.inputs.date', ['label' => 'Date', 'name' => 'post_date'])

                {{-- Place --}}
                @include('cms.components.inputs.text', ['label' => 'Place', 'name' => 'place', 'maxlength' => 255])

                {{-- Main Image --}}
                @include('cms.components.inputs.image', ['label' => 'Main Image', 'name' => 'main_image'])

                <div class="form-group">
                    <label class="form-control-label d-block">Other Images (Gallery)</label>
                    <input name="gallery_images[]" type="file" multiple class="form-control form-control-alternative">
                </div>

                <div class="form-group">
                    <label class="form-control-label d-block">Upload Any File (PDF/Word)</label>
                    <input name="any_file" type="file" class="form-control form-control-alternative">
                    @include('cms.components.inputs.error', ['name' => 'any_file'])
                </div>

                {{-- Short Description --}}
                @include('cms.components.inputs.textarea', ['label' => 'Short Description', 'asterix' => true, 'name' => 'short_description'])

                {{-- Description --}}
                @include('cms.components.inputs.textarea', ['label' => 'Description', 'name' => 'description', 'quill' => true])

                @can('syndicate_activities-publish')
                {{-- Published --}}
                @include('cms.components.inputs.checkbox', ['label' => 'Published', 'name' => 'publish_status', 'value' => 1, 'text' => 'Leave this off to save it as pending instead - you can publish it later from the list.'])
                @else
                <small><em>This activity will stay pending until someone with publish rights approves it.</em></small>
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
