@php
    //to became same as the permission name in the database
    $permission_prefix = str_replace('-', '_', $page_info['link']);
@endphp
<div class="card-header border-0">
    <div class="row align-items-center">
        <div class="col">
            <h3 class="mb-0">{{ $page_info['title'] }}</h3>
            @include('cms.layouts.pagination.select-nbr-of-entries')
        </div>
        <div class="col-auto text-right">
            @can($permission_prefix . '-create')
                <a href="{{ route('admin.' . $page_info['link'] . '.create') }}"
                    class="btn btn-sm btn-primary">Add</a>
            @endcan
            @can($permission_prefix . '-order')
                <a href="{{ route('admin.'.$page_info['link'].'.order') }}" class="btn btn-sm btn-warning">Order</a>
            @endcan

            {{-- search component --}}
            <div class="mt-2">
                @include('cms.layouts.pagination.search', ['columns' => $columns])
            </div>
        </div>
    </div>
</div>