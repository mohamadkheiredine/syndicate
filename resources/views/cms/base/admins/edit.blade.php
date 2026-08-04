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
            
            <form method="post" action="{{ route('admin.'.$page_info['link'].'.update', $row) }}" autocomplete="off">
                @csrf
                @method('put')

                {{-- First Name --}}
                @include('cms.components.inputs.text', ['label' => 'First Name', 'asterix' => true, 'name' => 'first_name', 'maxlength' => 255])

                {{-- Last Name --}}
                @include('cms.components.inputs.text', ['label' => 'Last Name', 'asterix' => true, 'name' => 'last_name', 'maxlength' => 255])

                {{-- Email --}}
                @include('cms.components.inputs.text', ['label' => 'Email', 'type' => 'email', 'asterix' => true, 'name' => 'email', 'maxlength' => 255])

                {{-- Password --}}
                @include('cms.components.inputs.text', ['label' => 'Password', 'type' => 'password', 'name' => 'password'])

                {{-- Confirm Password --}}
                @include('cms.components.inputs.text', ['label' => 'Confirm Password', 'type' => 'password', 'name' => 'confirm_password'])

                {{-- Role --}}
                <div class="form-group">
                    <label class="form-control-label" for="input-roles">Roles <span class="text-danger">*</span></label>
                    <select class="select2-custom form-control form-custom" name="roles[]" multiple>
                        @foreach($roles as $role)
                        <option value="{{ $role }}" @if(old('roles')) {{ in_array($role, old('roles')) ? 'selected' : '' }} @else {{ (isset($userRole) && in_array($role, $userRole)) ? 'selected' : '' }} @endif>{{ $role }}</option>
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