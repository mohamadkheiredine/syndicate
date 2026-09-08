@extends('cms.layouts.main')

@section('content')
@include('cms.layouts.headers.partials', ['title' => 'Edit Syndicate Family Member'])

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

                {{-- Name --}}
                @include('cms.components.inputs.text', ['label' => 'Name', 'asterix' => true, 'name' => 'name', 'maxlength' => 255, 'row' => $row])

                {{-- Image --}}
                @include('cms.components.inputs.image', ['label' => 'Syndicate Family Image', 'asterix' => true, 'name' => 'main_image', 'value' => $row->main_image])

                {{-- Designation --}}
                @include('cms.components.inputs.text', ['label' => 'Designation', 'asterix' => true, 'name' => 'designation', 'maxlength' => 255, 'row' => $row])

                {{-- Year --}}
                @include('cms.components.inputs.text', ['label' => 'Year', 'asterix' => true, 'name' => 'syndicate_year', 'maxlength' => 255, 'row' => $row, 'text' => 'e.g. 2023-2027'])

                @can('syndicate_family-publish')
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
