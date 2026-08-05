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
                    <a href="{{ route('admin.'.$page_info['link'].'.reset-password', $row) }}" class="btn btn-sm btn-warning">Reset Password</a>
                    <a href="{{ route('admin.'.$page_info['link'].'.index') }}" class="btn btn-sm btn-primary">Back to list</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            @include('cms.components.alert', ['with' => 'status', 'bg' => 'success'])

            <form method="post" action="{{ route('admin.'.$page_info['link'].'.update', $row) }}" enctype="multipart/form-data" autocomplete="off">
                @csrf
                @method('put')

                {{-- Photo --}}
                @include('cms.components.inputs.image', ['label' => 'Photo', 'name' => 'photo'])

                {{-- First Name --}}
                @include('cms.components.inputs.text', ['label' => 'First Name', 'asterix' => true, 'name' => 'first_name', 'maxlength' => 255])

                {{-- Father's Name --}}
                @include('cms.components.inputs.text', ['label' => "Father's Name", 'asterix' => true, 'name' => 'fathers_name', 'maxlength' => 255])

                {{-- Last Name --}}
                @include('cms.components.inputs.text', ['label' => 'Last Name', 'asterix' => true, 'name' => 'last_name', 'maxlength' => 255])

                {{-- Date Of Birth --}}
                @include('cms.components.inputs.date', ['label' => 'Date Of Birth', 'name' => 'dob'])

                {{-- Email --}}
                @include('cms.components.inputs.text', ['label' => 'Email', 'type' => 'email', 'asterix' => true, 'name' => 'email', 'maxlength' => 255])

                {{-- Mobile Number --}}
                @include('cms.components.inputs.text', ['label' => 'Mobile Number', 'asterix' => true, 'name' => 'mobile_number', 'maxlength' => 255])

                {{-- Blood Type --}}
                @include('cms.components.inputs.text', ['label' => 'Blood Type', 'asterix' => true, 'name' => 'blood_type', 'maxlength' => 10])

                {{-- Has ID Card --}}
                @include('cms.components.inputs.checkbox', ['label' => 'Has ID Card', 'name' => 'has_id', 'value' => true])

                {{-- Company --}}
                @include('cms.components.inputs.text', ['label' => 'Company Name', 'asterix' => true, 'name' => 'company', 'maxlength' => 255])

                {{-- Department --}}
                @include('cms.components.inputs.text', ['label' => 'Department', 'name' => 'department', 'maxlength' => 255])

                {{-- Unit --}}
                @include('cms.components.inputs.text', ['label' => 'Unit', 'name' => 'unit', 'maxlength' => 255])

                {{-- Date Of Employment --}}
                @include('cms.components.inputs.date', ['label' => 'Date Of Employment', 'name' => 'date_employment'])

                {{-- Registration Date --}}
                @include('cms.components.inputs.date', ['label' => 'Registration Date', 'name' => 'registration_date'])

                {{-- Registration Fees --}}
                @include('cms.components.inputs.number', ['label' => 'Registration Fees', 'name' => 'registration_fees'])

                {{-- Kaza --}}
                @include('cms.components.inputs.text', ['label' => 'Kaza', 'name' => 'kaza', 'maxlength' => 255])

                {{-- City --}}
                @include('cms.components.inputs.text', ['label' => 'City', 'name' => 'city', 'maxlength' => 255])

                {{-- Street --}}
                @include('cms.components.inputs.text', ['label' => 'Street', 'name' => 'street', 'maxlength' => 255])

                {{-- Building --}}
                @include('cms.components.inputs.text', ['label' => 'Building', 'name' => 'building', 'maxlength' => 255])

                {{-- Floor --}}
                @include('cms.components.inputs.text', ['label' => 'Floor', 'name' => 'floor', 'maxlength' => 255])

                <div class="text-center">
                    <button type="submit" class="btn btn-success mt-4">Save</button>
                </div>
            </form>
        </div>
    </div>

    @include('cms.layouts.footers.auth')
</div>
@endsection
