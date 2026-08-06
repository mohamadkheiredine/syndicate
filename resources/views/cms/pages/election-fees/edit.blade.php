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

                <div class="form-group">
                    <label class="form-control-label">Syndicate User <span class="text-danger">*</span></label>
                    <select name="user_id" class="select2-custom form-control" required>
                        <option value="" disabled>Choose User</option>
                        @foreach($users as $user)
                        <option value="{{ $user->id }}" @if(old('user_id', $row->user_id) == $user->id) selected @endif>
                            {{ $user->first_name }} {{ $user->fathers_name }} {{ $user->last_name }} - {{ $user->email }}
                        </option>
                        @endforeach
                    </select>
                    @include('cms.components.inputs.error', ['name' => 'user_id'])
                </div>

                {{-- Amount --}}
                @include('cms.components.inputs.number', ['label' => 'Amount', 'asterix' => true, 'name' => 'amount'])

                <div class="text-center">
                    <button type="submit" class="btn btn-success mt-4">Save</button>
                </div>
            </form>
        </div>
    </div>

    @include('cms.layouts.footers.auth')
</div>
@endsection
