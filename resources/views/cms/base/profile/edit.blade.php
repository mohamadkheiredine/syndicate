@extends('cms.layouts.main')

@section('content')
@include('cms.layouts.headers.partials', ['title' => 'Hello' . ' ' . Auth::guard('admin')->user()->first_name, 'description' => 'This is your profile page.'])

<div class="container-fluid mt--7">
    <div class="card bg-secondary shadow">
        <div class="card-header bg-white border-0">
            <div class="row align-items-center">
                <h3 class="col-12 mb-0">Edit Profile</h3>
            </div>
        </div>
        <div class="card-body">
            @include('cms.components.alert', ['with' => 'status', 'bg' => 'success'])
            
            <div class="row">
                <div class="col-lg-6">                    
                    <form method="post" action="{{ route('admin.profile.update') }}" autocomplete="off">
                        @csrf
                        @method('put')

                        <h6 class="heading-small text-muted mb-3">User information</h6>

                        {{-- First Name --}}
                        @include('cms.components.inputs.text', ['label' => 'First Name', 'asterix' => true, 'name' => 'first_name', 'value' => old('first_name', Auth::guard('admin')->user()->first_name), 'maxlength' => 255])

                        {{-- Last Name --}}
                        @include('cms.components.inputs.text', ['label' => 'Last Name', 'asterix' => true, 'name' => 'last_name', 'value' => old('last_name', Auth::guard('admin')->user()->last_name), 'maxlength' => 255])

                        {{-- Email --}}
                        @include('cms.components.inputs.text', ['label' => 'Email', 'type' => 'email', 'asterix' => true, 'name' => 'email', 'value' => old('email', Auth::guard('admin')->user()->email), 'maxlength' => 255])

                        <div class="text-center">
                            <button type="submit" class="btn btn-success mt-4">Save</button>
                        </div>
                    </form>
                </div>
                <div class="col-lg-6">
                    <form method="post" action="{{ route('admin.profile.password') }}" autocomplete="off">
                        @csrf
                        @method('put')

                        <h6 class="heading-small text-muted mb-3">Password</h6>

                        {{-- Old Password --}}
                        @include('cms.components.inputs.text', ['label' => 'Old Password', 'type' => 'password', 'asterix' => true, 'name' => 'old_password'])

                        {{-- Password --}}
                        @include('cms.components.inputs.text', ['label' => 'Password', 'type' => 'password', 'asterix' => true, 'name' => 'password'])

                        {{-- Confirm Password --}}
                        @include('cms.components.inputs.text', ['label' => 'Confirm Password', 'type' => 'password', 'asterix' => true, 'name' => 'confirm_password'])

                        <div class="text-center">
                            <button type="submit" class="btn btn-success mt-4">Change password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('cms.layouts.footers.auth')
</div>
@endsection