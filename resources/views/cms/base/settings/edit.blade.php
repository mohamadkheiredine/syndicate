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

                    <form method="post" action="{{ route('admin.'.$page_info['link'].'.edit') }}" enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        @method('put')

                        {{-- Android App --}}
                        @include('cms.components.inputs.text', ['label' => 'Android App', 'name' => 'android_app'])

                        {{-- Apple App --}}
                        @include('cms.components.inputs.text', ['label' => 'Apple App', 'name' => 'apple_app'])

                        <hr class="my-4">
                        <h6 class="heading-small text-muted mb-4">Force Update</h6>

                        <div class="row">
                            <div class="col-lg-6">
                                {{-- Title --}}
                                @include('cms.components.inputs.text', ['label' => 'Title', 'name' => 'title', 'maxlength' => 255])

                                {{-- Text --}}
                                @include('cms.components.inputs.textarea', ['label' => 'Text', 'name' => 'text', 'quill' => true])
                            </div>
                            <div class="col-lg-6">
                                <div class="row mb-3">
                                    <div class="col-sm-6">
                                        {{-- Android Version --}}
                                        @include('cms.components.inputs.text', ['label' => 'Android Version', 'name' => 'android_version'])
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="d-block form-control-label">Force Update Android</label>
                                        <label class="custom-toggle mb-0">
                                            <input type="checkbox" name="force_update_android" value="1" @if($row->force_update_android) {{ "checked" }} @endif>
                                            <span class="custom-toggle-slider rounded-circle"></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        {{-- iOS Version --}}
                                        @include('cms.components.inputs.text', ['label' => 'iOS Version', 'name' => 'ios_version'])
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="d-block form-control-label">Force Update iOS</label>
                                        <label class="custom-toggle mb-0">
                                            <input type="checkbox" name="force_update_ios" value="1" @if($row->force_update_ios) {{ "checked" }} @endif>
                                            <span class="custom-toggle-slider rounded-circle"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

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
