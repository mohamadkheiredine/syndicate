@extends('cms.layouts.main')

@section('content')
@include('cms.layouts.headers.partials', ['title' => 'Add Advertisement'])

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
            <form method="post" action="{{ route('admin.'.$page_info['link'].'.store') }}" autocomplete="off">
                @csrf

                {{-- Advertisement Type --}}
                @include('cms.components.inputs.text', ['label' => 'Advertisement Type', 'asterix' => true, 'name' => 'advertise_type', 'maxlength' => 255])

                {{-- Title --}}
                @include('cms.components.inputs.text', ['label' => 'Title', 'asterix' => true, 'name' => 'title', 'maxlength' => 255])

                {{-- Dimension --}}
                @include('cms.components.inputs.text', ['label' => 'Dimension', 'asterix' => true, 'name' => 'dimension', 'maxlength' => 255, 'text' => 'e.g. 300x200 px'])

                {{-- Type --}}
                @include('cms.components.inputs.text', ['label' => 'Type', 'asterix' => true, 'name' => 'what_type', 'maxlength' => 255, 'text' => 'e.g. Still'])

                {{-- Price --}}
                @include('cms.components.inputs.text', ['label' => 'Price', 'asterix' => true, 'name' => 'price', 'maxlength' => 255])

                {{-- Duration --}}
                @include('cms.components.inputs.text', ['label' => 'Duration', 'asterix' => true, 'name' => 'duration', 'maxlength' => 255, 'text' => 'e.g. ONE MONTH!'])

                @can('syndicate_advertisement-publish')
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
