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

                {{-- Mobile Number --}}
                @include('cms.components.inputs.text', ['label' => 'Mobile Number', 'type' => 'number', 'asterix' => true, 'name' => 'mobile_number', 'maxlength' => 255])

                {{-- Password --}}
                @include('cms.components.inputs.text', ['label' => 'Password', 'type' => 'password', 'asterix' => true, 'name' => 'password'])

                {{-- Confirm Password --}}
                @include('cms.components.inputs.text', ['label' => 'Confirm Password', 'type' => 'password', 'asterix' => true, 'name' => 'confirm_password'])

                <div class="text-center">
                    <button type="submit" class="btn btn-success mt-4">Save</button>
                </div>
            </form>
        </div>
    </div>

    @include('cms.layouts.footers.auth')
</div>
@endsection