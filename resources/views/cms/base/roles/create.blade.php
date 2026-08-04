@extends('cms.layouts.main')

@section('content')
@include('cms.layouts.headers.partials', ['title' => 'Add Record'])

<div class="container-fluid mt--7">
    <div class="row">
        <div class="col-xl-12 order-xl-1">
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

                        {{-- Name --}}
                        <div class="form-group">
                            <label class="form-control-label" for="input-name">Name <span class="text-danger">*</span></label>
                            <input name="name" id="input-name" class="form-control form-control-alternative{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="Name" value="{{ old('name') }}" autofocus>

                            @if($errors->has('name'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                            @endif
                        </div>

                        {{-- Permissions --}}
                        <div class="form-group">
                            <label class="form-control-label">Permissions</label>
                            @foreach($permission as $permission_row)
                            <div class="row">
                                <div class="col-md-3 align-self-center">
                                    <p class="text-capitalize mb-0">{{ str_replace('_', ' ', explode('-', $permission_row[0]->name)[0]) }}</p>
                                </div>
                                <div class="col-md-9">
                                    <div class="row">
                                        @foreach($permission_row as $value)
                                        <div class="col-auto">
                                            <p class="small text-capitalize mb-0">{{ str_replace('_', ' ', explode('-', $value->name)[1]) }}</p>
                                            <label class="custom-toggle mb-0">
                                                <input type="checkbox" value="{{ $value->id }}" name="permission[]" @if(old('permission')) {{ in_array($value->id, old('permission')) ? 'checked' : '' }} @endif>
                                                <span class="custom-toggle-slider rounded-circle"></span>
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <hr class="my-3">
                            @endforeach
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