@extends('cms.layouts.main')

@section('content')
@include('cms.layouts.headers.partials', ['title' => 'Edit Offer'])

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

                {{-- Title --}}
                @include('cms.components.inputs.text', ['label' => 'Title', 'asterix' => true, 'name' => 'title', 'maxlength' => 255, 'row' => $row])

                {{-- Offers Image --}}
                @include('cms.components.inputs.image', ['label' => 'Offers Image', 'name' => 'main_image', 'value' => $row->main_image])

                {{-- New Image --}}
                @include('cms.components.inputs.image', ['label' => 'New Image', 'name' => 'new_image', 'value' => $row->new_image])

                <div class="form-group">
                    <label class="form-control-label d-block">PDF</label>
                    @if($row->pdf)
                    <p><a href="{{ $row->pdf }}" target="_blank">Current file</a></p>
                    @endif
                    <input name="pdf" type="file" class="form-control form-control-alternative">
                    @include('cms.components.inputs.error', ['name' => 'pdf'])
                </div>

                {{-- Start Date --}}
                @include('cms.components.inputs.date', ['label' => 'Start Date', 'asterix' => true, 'name' => 'start_date', 'row' => $row])

                {{-- End Date --}}
                @include('cms.components.inputs.date', ['label' => 'End Date', 'asterix' => true, 'name' => 'end_date', 'row' => $row])

                {{-- Place --}}
                @include('cms.components.inputs.text', ['label' => 'Place', 'asterix' => true, 'name' => 'place', 'maxlength' => 255, 'row' => $row])

                {{-- Discount/Offer --}}
                @include('cms.components.inputs.text', ['label' => 'Discount/Offer', 'asterix' => true, 'name' => 'offers', 'maxlength' => 255, 'row' => $row])

                {{-- Short Description --}}
                @include('cms.components.inputs.textarea', ['label' => 'Short Description', 'asterix' => true, 'name' => 'short_description', 'row' => $row])

                {{-- Description --}}
                @include('cms.components.inputs.textarea', ['label' => 'Description', 'asterix' => true, 'name' => 'description', 'quill' => true, 'row' => $row])

                @can('syndicate_offers-publish')
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
