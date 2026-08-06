@extends('cms.layouts.main')

@section('content')
@include('cms.layouts.headers.cards')

<div class="container-fluid mt--7">
    <div class="card shadow mt-5">
        {{-- include data table header --}}
        @include('cms.layouts.pagination.datatable-header', ['columns' => []])

        @can('election_fees-export')
        <div class="px-4 pb-3 text-right">
            <a href="{{ route('admin.'.$page_info['link'].'.export.csv', request()->query()) }}" class="btn btn-sm btn-outline-secondary">CSV</a>
            <a href="{{ route('admin.'.$page_info['link'].'.export.excel', request()->query()) }}" class="btn btn-sm btn-outline-secondary">Excel</a>
            <a href="{{ route('admin.'.$page_info['link'].'.export.pdf', request()->query()) }}" class="btn btn-sm btn-outline-secondary">PDF</a>
        </div>
        @endcan

        @include('cms.components.alert', ['with' => 'status', 'bg' => 'success', 'class' => 'mx-4'])

        <div class="table-responsive">
        <table class="table align-items-center table-flush">
            <thead class="thead-light">
                <tr>
                    <th scope="col" class="no-sort">User Info</th>
                    @include('cms.layouts.pagination.sort-col', ['col_name' => 'amount', 'col_title' => 'Amount'])
                    @include('cms.layouts.pagination.sort-col', ['col_name' => 'created_at', 'col_title' => 'Date Added'])
                    <th scope="col" class="no-sort"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $row)
                <tr>
                    <td>
                        Full Name: <strong>{{ $row->user ? $row->user->first_name.' '.$row->user->fathers_name.' '.$row->user->last_name : '' }}</strong><br>
                        Email: <strong>{{ $row->user->email ?? '' }}</strong><br>
                        Company: <strong>{{ $row->user->company ?? '' }}</strong>
                    </td>
                    <td>{{ $row->amount }}</td>
                    <td>{{ $row->created_at ? $row->created_at->format('Y-m-d H:i:s') : '' }}</td>
                    <td class="text-right">
                        <div class="dropdown">
                            <a class="btn btn-sm btn-icon-only text-light" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                @can('election_fees-edit')
                                <a class="dropdown-item" href="{{ route('admin.'.$page_info['link'].'.edit', $row) }}">Edit</a>
                                @endcan

                                @can('election_fees-delete')
                                <form action="{{ route('admin.'.$page_info['link'].'.destroy', $row) }}" method="post">
                                    @csrf
                                    @method('delete')

                                    <button type="button" class="dropdown-item" onclick="confirm('Are you sure you want to delete this record?') ? this.parentElement.submit() : ''">
                                        Delete
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>

    {{-- custom pagination links --}}
    @include('cms.layouts.pagination.pagination-links')

    @include('cms.layouts.footers.auth')
</div>
@endsection
