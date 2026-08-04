@extends('cms.layouts.main')

@section('content')
@include('cms.layouts.headers.cards')

<div class="container-fluid mt--7">
	<div class="card text-center shadow overflow-hidden mb-5">
		<div class="card-header border-0">
			<h1 class="mb-0">Welcome <b>{{ Auth::guard('admin')->user()->first_name }}</b> to {{ env('APP_NAME') }} dashboard</h1>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-3 mb-4">
			<div class="card card-stats h-100">
				<div class="card-body">
					<div class="row">
						<div class="col">
							<h5 class="card-title text-uppercase text-muted mb-0">Total Users Registered</h5>
							<span class="h2 font-weight-bold mb-0">0</span>
						</div>
						<div class="col-auto">
							<div class="icon icon-shape bg-primary text-white rounded-circle shadow">
								<i class="fas fa-users"></i>
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
							<h5 class="card-title text-uppercase text-muted mb-0">#### ##### ######</h5>
							<span class="h2 font-weight-bold mb-0">0</span>
						</div>
						<div class="col-auto">
							<div class="icon icon-shape bg-primary text-white rounded-circle shadow">
								<i class="fas fa-users"></i>
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
							<h5 class="card-title text-uppercase text-muted mb-0">#### ##### ######</h5>
							<span class="h2 font-weight-bold mb-0">0</span>
						</div>
						<div class="col-auto">
							<div class="icon icon-shape bg-primary text-white rounded-circle shadow">
								<i class="fas fa-users"></i>
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
							<h5 class="card-title text-uppercase text-muted mb-0">#### ##### ######</h5>
							<span class="h2 font-weight-bold mb-0">0</span>
						</div>
						<div class="col-auto">
							<div class="icon icon-shape bg-primary text-white rounded-circle shadow">
								<i class="fas fa-users"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-6">
			<div class="card">
				<div class="card-header border-0">
					<div class="row align-items-center">
						<div class="col">
							<h3 class="mb-0">Forms & Submissions</h3>
						</div>
					</div>
				</div>
				<div class="table-responsive">
					<div class="table-responsive">
<table class="table align-items-center table-flush">
						<thead class="thead-light">
							<tr>
								<th scope="col">Forms</th>
								<th class="text-center" scope="col">Number of Submissions</th>
								<th scope="col"></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th scope="row">####### ####</th>
								<td class="text-center"><span class="badge badge-circle badge-primary">0</span></td>
								<td><a href="" class="btn btn-sm btn-primary">See all</a></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	@include('cms.layouts.footers.auth')
</div>
@endsection