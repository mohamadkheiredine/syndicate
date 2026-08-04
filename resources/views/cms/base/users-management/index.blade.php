@extends('cms.layouts.main')

@section('content')
@include('cms.layouts.headers.cards')

<div class="container-fluid mt--7">
    <div class="row mt-5">
        <div class="col">
            <div class="card shadow">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">{{ $page_info['title'] }}</h3>
                        </div>
                        <div class="col-auto text-right">
                            @can('admins-create')
                            <a href="{{ route('admin.'.$page_info['link'].'.create') }}" class="btn btn-sm btn-primary">Add</a>
                            @endcan
                        </div>
                    </div>
                </div>

                @include('cms.components.alert', ['with' => 'status', 'bg' => 'success', 'class' => 'mx-4'])

                <table class="table align-items-center table-flush datatable">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">Full Name</th>
                            <th scope="col">Email</th>
                            @can('admins-block')
                            <th scope="col" class="no-sort">Blocked</th>
                            @endcan
                            <th scope="col">Created At</th>
                            <th scope="col" class="no-sort"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rows as $row)
                        <tr>
                            <td>
                                <span class="badge badge-dot">
                                    <i class="bg-success"></i>
                                </span>
                                {{ $row->first_name . ' ' . $row->last_name }}
                            </td>
                            <td>{{ $row->email }}</td>
                            <td>
                                @if(!empty($row->getRoleNames()))
                                    @foreach($row->getRoleNames() as $v)
                                        <label class="badge badge-pill badge-success">{{ $v }}</label>
                                    @endforeach
                                @endif
                            </td>
                            @can('admins-block')
                            <td class="adjust-element">
                                {{-- Admin can not block himself --}}
                                @if(Auth::guard('admin')->user()->id != $row->id)
                                    {{-- All other Roles can not block: Developer --}}
                                    @if(!$row->hasRole('Developer'))
                                        <label class="custom-toggle mb-0">
                                            <input class="block-js" type="checkbox" value="{{ $row->id }}" @if($row->blocked) {{ "checked" }} @endif>
                                            <span class="custom-toggle-slider rounded-circle"></span>
                                        </label>
                                    @endif
                                @endif
                            </td>
                            @endcan
                            <td>{{ date('d M Y - h:i A', strtotime($row->created_at)) }}</td>
                            <td class="text-right">
                                <div class="dropdown">
                                    <a class="btn btn-sm btn-icon-only text-light" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                        @can('admins-edit')
                                            {{-- Admin can not edit himself redirect to profile page --}}
                                            @if(Auth::guard('admin')->user()->id != $row->id)
                                                {{-- All other Roles can not edit: Developer --}}
                                                @if(!$row->hasRole('Developer'))
                                                <a class="dropdown-item" href="{{ route('admin.'.$page_info['link'].'.edit', $row) }}">Edit</a>
                                                @endif
                                            @else
                                                <a class="dropdown-item" href="{{ route('admin.profile.edit') }}">Edit</a>
                                            @endif
                                        @endcan

                                        @can('admins-delete')
                                            {{-- Admin can not delete himself --}}
                                            @if(Auth::guard('admin')->user()->id != $row->id)
                                                {{-- All other Roles can not delete: Developer --}}
                                                @if(!$row->hasRole('Developer'))
                                                    <form action="{{ route('admin.'.$page_info['link'].'.destroy', $row) }}" method="post">
                                                        @csrf
                                                        @method('delete')

                                                        <button type="button" class="dropdown-item" onclick="confirm('Are you sure you want to delete this admin?') ? this.parentElement.submit() : ''">
                                                            Delete
                                                        </button>
                                                    </form>
                                                @endif
                                            @endif
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
    </div>

    @include('cms.layouts.footers.auth')
</div>
@endsection

@can('admins-block')
@push('script')
<script type="text/javascript">

    $('.block-js').change(function(){
        var $this = $(this);

        $.ajax({
            type: "post",
            url: "{{ route('admin.'.$page_info['link'].'.block') }}",
            data: {
                "_token": "{{ csrf_token() }}",
                "id": $this.val()
            },
            success: function(data){
                if(data.data == 1){
                    $this.closest("tr").find(".badge-dot i").removeClass("bg-success").addClass("bg-danger");
                } else {
                    $this.closest("tr").find(".badge-dot i").removeClass("bg-danger").addClass("bg-success");
                }
            }
        });

    });

</script>
@endpush
@endcan