@extends('cms.layouts.main')

@section('content')
@include('cms.layouts.headers.partials', ['title' => $page_info['title']])

<div class="container-fluid mt--7">
    <div class="card bg-secondary shadow">
        <div class="card-body">

            @include('cms.components.alert', ['with' => 'status', 'bg' => 'success'])

            <form method="post" action="{{ route('admin.'.$page_info['link'].'.edit') }}" autocomplete="off" enctype="multipart/form-data">
                @csrf
                @method('put')

                {{-- Image --}}
                @include('cms.components.inputs.image', ['label' => 'Image', 'name' => 'image', 'imageLink' => $row->image])

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
