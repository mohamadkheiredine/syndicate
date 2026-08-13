@extends('cms.layouts.main')

@section('content')
@include('cms.layouts.headers.cards')

<div class="container-fluid mt--7">
    <div class="row mt-5">
        <div class="col">
            <div class="card bg-secondary shadow">
                <div class="card-header bg-white border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">{{ $page_info['title'] }}</h3>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @include('cms.components.alert', ['with' => 'status', 'bg' => 'success'])

                    <form method="post" action="{{ route('admin.'.$page_info['link'].'.update') }}" enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        @method('put')

                        {{-- Image --}}
                        @include('cms.components.inputs.image', ['label' => 'Image', 'name' => 'main_image', 'value' => $row->main_image])

                        {{-- Short Description --}}
                        @include('cms.components.inputs.textarea', ['label' => 'Short Description', 'asterix' => true, 'name' => 'short_description', 'row' => $row])

                        @can('our_team-publish')
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
        </div>
    </div>

    @include('cms.layouts.footers.auth')
</div>
@endsection
