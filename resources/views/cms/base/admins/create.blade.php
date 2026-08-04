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
            <form method="post" action="{{ route('admin.'.$page_info['link'].'.store') }}" autocomplete="off">
                @csrf

                {{-- First Name --}}
                @include('cms.components.inputs.text', ['label' => 'First Name', 'asterix' => true, 'name' => 'first_name', 'maxlength' => 255])

                {{-- Last Name --}}
                @include('cms.components.inputs.text', ['label' => 'Last Name', 'asterix' => true, 'name' => 'last_name', 'maxlength' => 255])

                {{-- Email --}}
                @include('cms.components.inputs.text', ['label' => 'Email', 'type' => 'email', 'asterix' => true, 'name' => 'email', 'maxlength' => 255])

                {{-- Time Zone --}}
                <div class="form-group">
                <label class="form-control-label">Timezone @include('cms.components.inputs.asterix')</label>
                <select name="timezone" id="timezone" class="select2-custom form-control">
                    @foreach (timezone_identifiers_list() as $timezone)
                            <option value="{{ $timezone }}"{{ $timezone == old('timezone') ? ' selected' : '' }}>{{ $timezone }}</option>
                    @endforeach
                    </select>
                </div>
                {{-- @include('cms.components.inputs.select-single', ['label' => 'Timezone', 'asterix' => true, 'name' => 'timezone', 'placeholder' => 'Please Choose a Timezone', 'rows' => timezone_identifiers_list(), 'value_attribute' => 'id', 'attribute' => 'title']) --}}

                {{-- Password --}}
                @include('cms.components.inputs.text', ['label' => 'Password', 'type' => 'password', 'asterix' => true, 'name' => 'password'])

                {{-- Confirm Password --}}
                @include('cms.components.inputs.text', ['label' => 'Confirm Password', 'type' => 'password', 'asterix' => true, 'name' => 'confirm_password'])

                {{-- Role --}}
                <div class="form-group">
                    <label class="form-control-label" for="input-roles">Roles <span class="text-danger">*</span></label>
                    <select class="select2-custom form-control form-custom" name="roles[]" multiple>
                        @foreach($roles as $role)
                        <option value="{{ $role }}" @if(old('roles')) {{ in_array($role, old('roles')) ? 'selected' : '' }} @endif>{{ $role }}</option>
                        @endforeach
                    </select>

                    @include('cms.components.inputs.error', ['name' => 'roles'])
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-success mt-4">Save</button>
                </div>
            </form>
        </div>
    </div>

    @include('cms.layouts.footers.auth')
</div>
@endsection