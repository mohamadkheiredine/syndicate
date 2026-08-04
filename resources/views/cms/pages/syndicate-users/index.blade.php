@extends('cms.layouts.main')

@section('content')
@include('cms.layouts.headers.cards')

<div class="container-fluid mt--7">
    <div class="card shadow mt-5">
        {{-- include data table header --}}
        @include('cms.layouts.pagination.datatable-header', ['columns' => $filterableColumns])

        @can('syndicate_users-export')
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
                    @include('cms.layouts.pagination.sort-col', ['col_name' => 'first_name', 'col_title' => 'Full Name'])
                    <th scope="col" class="no-sort">DOB</th>
                    @include('cms.layouts.pagination.sort-col', ['col_name' => 'email', 'col_title' => 'Email'])
                    <th scope="col" class="no-sort">Profile Image</th>
                    <th scope="col" class="no-sort">Mobile Number</th>
                    <th scope="col" class="no-sort">Company Info</th>
                    <th scope="col" class="no-sort">Blood Type</th>
                    <th scope="col" class="no-sort">Registration Info</th>
                    <th scope="col" class="no-sort">Address</th>
                    <th scope="col" class="no-sort">Has ID Card</th>
                    <th scope="col" class="no-sort">Status</th>
                    <th scope="col" class="no-sort"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $row)
                <tr>
                    <td>{{ $row->first_name }} {{ $row->fathers_name }} {{ $row->last_name }}</td>
                    <td>{{ $row->dob ? $row->dob->format('Y-m-d') : 'n/a' }}</td>
                    <td><a href="mailto:{{ $row->email }}">{{ $row->email }}</a></td>
                    <td>
                        @if($row->photo)
                        <img src="{{ $row->photo }}" width="80px" class="img-thumbnail">
                        @endif
                    </td>
                    <td>{{ $row->mobile_number }}</td>
                    <td>
                        Company: <strong>{{ $row->company }}</strong><br>
                        Department: <strong>{{ $row->department }}</strong><br>
                        Unit: <strong>{{ $row->unit }}</strong><br>
                        Date Employment: <strong>{{ $row->date_employment ? $row->date_employment->format('Y-m-d') : 'n/a' }}</strong>
                    </td>
                    <td>{{ $row->blood_type }}</td>
                    <td>
                        Date: <strong>{{ $row->registration_date ? $row->registration_date->format('Y-m-d') : 'n/a' }}</strong><br>
                        Fees: <strong>{{ $row->registration_fees }}</strong>
                    </td>
                    <td>
                        Kaza: <strong>{{ $row->kaza }}</strong><br>
                        City: <strong>{{ $row->city }}</strong><br>
                        Street: <strong>{{ $row->street }}</strong><br>
                        Building: <strong>{{ $row->building }}</strong><br>
                        Floor: <strong>{{ $row->floor }}</strong>
                    </td>
                    <td>{{ $row->has_id ? 'YES' : 'NO' }}</td>
                    <td>
                        @if($row->activation_code == 'activated')
                        <span class="badge badge-success">Activated</span>
                        @else
                        <span class="badge badge-warning">Deactivated</span>
                        @endif
                    </td>
                    <td class="text-right">
                        <div class="dropdown">
                            <a class="btn btn-sm btn-icon-only text-light" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                @can('syndicate_users-edit')
                                <a class="dropdown-item" href="{{ route('admin.'.$page_info['link'].'.edit', $row) }}">Edit</a>
                                @endcan

                                @can('syndicate_users-activate')
                                <form action="{{ route('admin.'.$page_info['link'].'.activate') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $row->id }}">
                                    <button type="submit" class="dropdown-item">
                                        {{ $row->activation_code == 'activated' ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                                @endcan

                                @can('syndicate_users-reset_password')
                                <a class="dropdown-item" href="{{ route('admin.'.$page_info['link'].'.reset-password', $row) }}">Reset Password</a>
                                @endcan

                                @can('syndicate_users-delete')
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
