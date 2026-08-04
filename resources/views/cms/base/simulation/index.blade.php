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
                    @if(session()->has('simulation'))
                    <div class="mb-3">
                        <div class="alert alert-success mb-0" role="alert">
                            You are simulating as {{ $simulating_user->full_name }}!
                        </div>
                        <a class="small" href="{{ route('admin.simulation.destroy') }}">Leave simulation</a>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('admin.simulation.store') }}" autocomplete="off">
                        @csrf

                        {{-- User --}}
                        @include('cms.components.inputs.select-single', ['label' => 'User', 'asterix' => true, 'name' => 'user_id', 'placeholder' => 'Please select a User', 'rows' => $users, 'value_attribute' => 'id', 'attribute' => 'full_name'])

                        <button type="submit" class="btn btn-success mt-4">Simulate</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('cms.layouts.footers.auth')
</div>
@endsection
