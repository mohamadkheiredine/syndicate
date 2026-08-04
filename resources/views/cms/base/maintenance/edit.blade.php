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

                        {{-- Maintenance Mode --}}
                        @include('cms.components.inputs.checkbox', ['label' => 'Maintenance Mode', 'name' => 'maintenance_mode', 'value' => true])

                        {{-- Image --}}
                        @include('cms.components.inputs.image', ['label' => 'Image', 'asterix' => false, 'name' => 'image'])

                        {{-- Title --}}
                        @include('cms.components.inputs.text', ['label' => 'Title', 'asterix' => true, 'name' => 'title', 'maxlength' => 255])

                        {{-- Text --}}
                        @include('cms.components.inputs.textarea', ['label' => 'Text', 'asterix' => true, 'name' => 'text', 'quill' => true])

                        {{-- Secret --}}
                        @include('cms.components.inputs.text', ['label' => 'Secret', 'asterix' => true, 'name' => 'secret', 'text' => 'Use the following link to bypass the maintenance mode: '.url('/').'/'.$row->secret])

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