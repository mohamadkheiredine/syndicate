@extends('cms.layouts.main')

@section('content')
@include('cms.layouts.headers.partials', ['title' => 'Edit Record'])

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

            <form method="post" action="{{ route('admin.'.$page_info['link'].'.update', $row) }}" enctype="multipart/form-data" autocomplete="off">
                @csrf
                @method('put')

                {{-- Publish --}}
                @include('cms.components.inputs.checkbox', ['label' => 'Publish', 'name' => 'publish', 'value' => true])

                {{-- Icon --}}
                @include('cms.components.inputs.image', ['label' => 'Icon', 'asterix' => true, 'name' => 'icon'])

                {{-- Title --}}
                @include('cms.components.inputs.text', ['label' => 'Title', 'asterix' => true, 'name' => 'title', 'maxlength' => 255])

                {{-- Link --}}
                @include('cms.components.inputs.text', ['label' => 'Link', 'asterix' => true, 'name' => 'link', 'maxlength' => 255])

                <div class="text-center">
                    <button type="submit" class="btn btn-success mt-4">Save</button>
                </div>
            </form>
        </div>
    </div>

    @include('cms.layouts.footers.auth')
</div>
@endsection