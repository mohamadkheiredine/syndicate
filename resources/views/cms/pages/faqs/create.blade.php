@extends('cms.layouts.main')

@section('content')
@include('cms.layouts.headers.partials', ['title' => 'Add Record'])

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

                {{-- Publish --}}
                @include('cms.components.inputs.checkbox', ['label' => 'Publish', 'name' => 'publish', 'value' => true])

                {{-- Title --}}
                @include('cms.components.inputs.text', ['label' => 'Title', 'asterix' => true, 'name' => 'title', 'maxlength' => 255])

                {{-- Text --}}
                @include('cms.components.inputs.textarea', ['label' => 'Text', 'asterix' => true, 'name' => 'text', 'quill' => true])

                <div class="text-center">
                    <button type="submit" class="btn btn-success mt-4">Save</button>
                </div>
            </form>
        </div>
    </div>

    @include('cms.layouts.footers.auth')
</div>
@endsection