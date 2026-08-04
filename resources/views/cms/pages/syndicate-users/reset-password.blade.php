@extends('cms.layouts.main')

@section('content')
@include('cms.layouts.headers.partials', ['title' => 'Reset Password'])

<div class="container-fluid mt--7">
    <div class="card bg-secondary shadow">
        <div class="card-header bg-white border-0">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="mb-0">Reset Password &mdash; {{ $row->first_name }} {{ $row->last_name }}</h3>
                </div>
                <div class="col-auto text-right">
                    <a href="{{ route('admin.'.$page_info['link'].'.index') }}" class="btn btn-sm btn-primary">Back to list</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form method="post" action="{{ route('admin.'.$page_info['link'].'.reset-password.update') }}" autocomplete="off">
                @csrf
                <input type="hidden" name="user_id" value="{{ $row->id }}">

                {{-- Password --}}
                @include('cms.components.inputs.text', ['label' => 'New Password', 'type' => 'password', 'asterix' => true, 'name' => 'password', 'text' => 'The password must be at least 6 characters.'])

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
