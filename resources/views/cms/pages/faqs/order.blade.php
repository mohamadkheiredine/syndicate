@extends('cms.layouts.main')

@section('content')
@include('cms.layouts.headers.partials', ['title' => 'Order Records'])

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
                    @include('cms.components.alert', ['with' => 'status', 'bg' => 'success'])

                    @if(count($rows))
                    <form method="post">
                        @csrf
                        <ul class="sortable list-unstyled">
                            @foreach($rows as $row)
                            <li class="sortable-row bg-white border cursor-grab px-3 py-2 mb-2">
                                <input type="hidden" name="id[]" value="{{ $row['id'] }}">
                                <input type="hidden" name="pos[]" value="">
                                <small>{{ $row->title }}</small>
                            </li>
                            @endforeach
                        </ul>
                        <div class="text-center">
                            <button type="submit" class="btn btn-success mt-4">Submit</button>
                        </div>
                    </form>
                    @else
                    <p class="text-muted text-center m-0 py-4">No record found for sorting</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @include('cms.layouts.footers.auth')
</div>
@endsection