@extends('cms.layouts.main')

@section('content')
@include('cms.layouts.headers.cards')

<div class="container-fluid mt--7">
    <div class="card shadow mt-5">
        <div class="card-header border-0">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="mb-0">{{ $page_info['title'] }}</h3>
                    @include('cms.layouts.pagination.select-nbr-of-entries')
                </div>
                <div class="col-auto text-right">
                    @can('members_payment-create')
                    <a href="{{ route('admin.'.$page_info['link'].'.create') }}" class="btn btn-sm btn-primary">Add Payment</a>
                    @endcan
                </div>
            </div>

            <form action="{{ route('admin.'.$page_info['link'].'.index') }}" method="get" class="row mt-3">
                <div class="col-md-5">
                    <label class="form-control-label">Syndicate Users</label>
                    <select name="user_id" class="select2-custom form-control" onchange="this.form.submit()">
                        <option value="">All Users</option>
                        @foreach($users as $user)
                        <option value="{{ $user->id }}" @if($selected_user == $user->id) selected @endif>
                            {{ $user->first_name }} {{ $user->fathers_name }} {{ $user->last_name }} - {{ $user->email }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="form-control-label">Payment Year</label>
                    <select name="year" class="select2-custom form-control" onchange="this.form.submit()">
                        <option value="">All Years</option>
                        @foreach($years as $year)
                        <option value="{{ $year }}" @if($selected_year == $year) selected @endif>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
            </form>

            @if($total_due_amount !== null)
            <div class="alert alert-info mt-3">
                <strong>Total Due Amount:</strong> {{ number_format($total_due_amount, 2) }}
            </div>
            @endif
        </div>

        @can('members_payment-export')
        <div class="px-4 pb-3 text-right">
            <a href="{{ route('admin.'.$page_info['link'].'.export.csv', request()->query()) }}" class="btn btn-sm btn-outline-secondary">CSV</a>
            <a href="{{ route('admin.'.$page_info['link'].'.export.excel', request()->query()) }}" class="btn btn-sm btn-outline-secondary">Excel</a>
            <a href="{{ route('admin.'.$page_info['link'].'.export.pdf', request()->query()) }}" class="btn btn-sm btn-outline-secondary">PDF</a>
        </div>
        @endcan

        @include('cms.components.alert', ['with' => 'status', 'bg' => 'success', 'class' => 'mx-4'])
        @include('cms.components.alert', ['with' => 'error', 'bg' => 'danger', 'class' => 'mx-4'])

        <div class="table-responsive">
        <table class="table align-items-center table-flush">
            <thead class="thead-light">
                <tr>
                    <th scope="col">Full Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Company</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Year</th>
                    <th scope="col">Receipt Number</th>
                    <th scope="col">Date Added</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $row)
                <tr>
                    <td>{{ $row->user ? $row->user->first_name.' '.$row->user->fathers_name.' '.$row->user->last_name : '' }}</td>
                    <td>{{ $row->user->email ?? '' }}</td>
                    <td>{{ $row->user->company ?? '' }}</td>
                    <td>{{ $row->amount }}</td>
                    <td>{{ $row->ue_year }}</td>
                    <td>{{ $row->receipt }}</td>
                    <td>{{ $row->created_at ? $row->created_at->format('Y-m-d H:i:s') : '' }}</td>
                    <td class="text-right">
                        @can('members_payment-delete')
                        <form action="{{ route('admin.'.$page_info['link'].'.destroy', $row) }}" method="post">
                            @csrf
                            @method('delete')
                            <button type="button" class="btn btn-xs btn-danger" onclick="confirm('Are you sure you want to delete this payment?') ? this.parentElement.submit() : ''">
                                Delete
                            </button>
                        </form>
                        @endcan
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>

        {{-- real server-side pagination — only this page's rows were ever queried/sent --}}
        @include('cms.layouts.pagination.pagination-links')
    </div>

    @include('cms.layouts.footers.auth')
</div>
@endsection
