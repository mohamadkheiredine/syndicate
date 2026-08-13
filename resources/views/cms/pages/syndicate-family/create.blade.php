@extends('cms.layouts.main')

@section('content')
@include('cms.layouts.headers.partials', ['title' => 'Add Syndicate Family Member'])

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

                {{-- Name --}}
                @include('cms.components.inputs.text', ['label' => 'Name', 'asterix' => true, 'name' => 'name', 'maxlength' => 255])

                {{-- Image --}}
                @include('cms.components.inputs.image', ['label' => 'Syndicate Family Image', 'asterix' => true, 'name' => 'main_image'])

                {{-- Designation --}}
                @include('cms.components.inputs.text', ['label' => 'Designation', 'asterix' => true, 'name' => 'designation', 'maxlength' => 255])

                {{-- Year --}}
                @include('cms.components.inputs.text', ['label' => 'Year', 'asterix' => true, 'name' => 'syndicate_year', 'maxlength' => 255, 'text' => 'e.g. 2023-2027'])

                @can('syndicate_family-publish')
                {{-- Published --}}
                @include('cms.components.inputs.checkbox', ['label' => 'Published', 'name' => 'publish_status', 'value' => 1, 'text' => 'Leave this off to save it as pending instead - you can publish it later from the list.'])
                @else
                <small><em>This will stay pending until someone with publish rights approves it.</em></small>
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
