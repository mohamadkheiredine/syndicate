@extends('cms.layouts.main')

@section('content')
@include('cms.layouts.headers.cards')

<div class="container-fluid mt--7">
    <div class="row">
        <div class="col-lg-3 mb-4">
            <div class="card card-stats h-100">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Total Unpaid Subscriptions</h5>
                            <span class="h2 font-weight-bold mb-0">{{ number_format($total_unpaid_amount, 2) }}$</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                <i class="fas fa-exclamation-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 mb-4">
            <div class="card card-stats h-100">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Total Income</h5>
                            <span class="h2 font-weight-bold mb-0">{{ number_format($total_income, 2) }}$</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                                <i class="fas fa-arrow-up"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 mb-4">
            <div class="card card-stats h-100">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Total Expense</h5>
                            <span class="h2 font-weight-bold mb-0">{{ number_format($total_expense, 2) }}$</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                <i class="fas fa-arrow-down"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 mb-4">
            <div class="card card-stats h-100">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">Income - Expense</h5>
                            <span class="h2 font-weight-bold mb-0">{{ number_format($total_income - $total_expense, 2) }}$</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-primary text-white rounded-circle shadow">
                                <i class="fas fa-balance-scale"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header border-0">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="mb-0">Members Subscriptions</h3>
                </div>
            </div>

            <form action="{{ route('admin.reports.index') }}" method="get" class="row mt-3">
                <div class="col-md-4">
                    <label class="form-control-label">Payment Year</label>
                    <select name="year" class="select2-custom form-control" onchange="this.form.submit()">
                        <option value="">All Years</option>
                        @foreach($years as $year)
                        <option value="{{ $year }}" @if($selected_year == $year) selected @endif>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>

        <div class="card-body">
            @if(empty($payment_years))
            <p class="text-muted mb-0">No payments recorded{{ $selected_year != 'all' ? ' for '.$selected_year : '' }}.</p>
            @endif

            <div class="row">
                @foreach($payment_years as $year => $months)
                <div class="col-md-6 mb-4">
                    <h3>Year: {{ $year }}</h3>
                    <ul class="list-unstyled">
                        @foreach($months as $month => $amount)
                        <li><strong>{{ date('F', mktime(0, 0, 0, $month, 10)) }}</strong> &rArr; {{ number_format($amount, 2) }}$</li>
                        @endforeach
                    </ul>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    @include('cms.layouts.footers.auth')
</div>
@endsection
