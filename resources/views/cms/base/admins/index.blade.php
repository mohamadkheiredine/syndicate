@extends('cms.layouts.main')

@section('content')
    @include('cms.layouts.headers.cards')

    <div class="container-fluid mt--7">
        <div class="row mt-5">
            <div class="col">
                <div class="card shadow">
                    {{-- include data table header --}}
                    @include('cms.layouts.pagination.datatable-header', ['columns' => $filterableColumns, 'columnsTitles'])

                    @include('cms.components.alert', [
                        'with' => 'status',
                        'bg' => 'success',
                        'class' => 'mx-4',
                    ])

                    <div class="table-responsive">
<table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                              {{-- column fields for sorting --}}
                              @include('cms.layouts.pagination.sort-col', ['col_name' => 'first_name', 'col_title' => 'Name'])
                              @include('cms.layouts.pagination.sort-col', ['col_name' => 'email', 'col_title' => 'Email'])
                              <th scope="col" class="no-sort">Roles</th>
                              @can('admins-block')
                                  <th scope="col" class="no-sort">Blocked</th>
                              @endcan
                              @include('cms.layouts.pagination.sort-col', ['col_name' => 'created_at', 'col_title' => 'Created At'])
                              <th scope="col" class="no-sort"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                <tr>
                                    <td>
                                        <span class="badge badge-dot">
                                          @if ($row->blocked)
                                            <i class="bg-danger"></i>
                                          @else
                                            <i class="bg-success"></i>
                                          @endif
                                        </span>
                                        {{ $row->first_name . ' ' . $row->last_name }}
                                    </td>
                                    <td>{{ $row->email }}</td>
                                    <td>
                                        @if (!empty($row->getRoleNames()))
                                            @foreach ($row->getRoleNames() as $v)
                                                <label class="badge badge-pill badge-success">{{ $v }}</label>
                                            @endforeach
                                        @endif
                                    </td>
                                    @can('admins-block')
                                        <td class="adjust-element">
                                            {{-- Admin can not block himself --}}
                                            @if (Auth::guard('admin')->user()->id != $row->id)
                                                {{-- All other Roles can not block: developer or super-admin --}}
                                                @if (!$row->hasRole('developer') && !$row->hasRole('super-admin'))
                                                    <label class="custom-toggle mb-0">
                                                    <input onclick="block({{$row->id}})" class="block-js" type="checkbox" value="{{ $row->id }}" @if($row->blocked) {{ "checked" }} @endif>
                                                        <span class="custom-toggle-slider rounded-circle"></span>
                                                    </label>
                                                @endif
                                            @endif
                                        </td>
                                    @endcan
                                    <td>{{ date('d M Y - h:i A', strtotime($row->created_at)) }}</td>
                                    {{-- <td>{{ $row->created_at }}</td> --}}
                                    <td class="text-right">
                                        <div class="dropdown">
                                            <a class="btn btn-sm btn-icon-only text-light" role="button"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                @can('admins-edit')
                                                    @if($row->id != 1)
                                                        {{-- Admin can not edit himself redirect to profile page --}}
                                                        @if (Auth::guard('admin')->user()->id != $row->id)
                                                            {{-- All other Roles can not edit: developer or super-admin --}}
                                                            @if (!$row->hasRole('developer') && !$row->hasRole('super-admin'))
                                                                <a class="dropdown-item"
                                                                    href="{{ route('admin.' . $page_info['link'] . '.edit', $row) }}">Edit</a>
                                                            @endif
                                                        @else
                                                            <a class="dropdown-item"
                                                                href="{{ route('admin.profile.edit') }}">Edit</a>
                                                        @endif
                                                    @endif
                                                @endcan

                                                @can('admins-delete')
                                                    @if($row->id != 1)
                                                        {{-- Admin can not delete himself --}}
                                                        @if (Auth::guard('admin')->user()->id != $row->id)
                                                            {{-- All other Roles can not delete: developer or super-admin --}}
                                                            @if (!$row->hasRole('developer') && !$row->hasRole('super-admin'))
                                                                <form
                                                                    action="{{ route('admin.' . $page_info['link'] . '.destroy', $row) }}"
                                                                    method="post">
                                                                    @csrf
                                                                    @method('delete')

                                                                    <button type="button" class="dropdown-item"
                                                                        onclick="confirm('Are you sure you want to delete this admin?') ? this.parentElement.submit() : ''">
                                                                        Delete
                                                                    </button>
                                                                </form>
                                                            @endif
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
                {{-- custom pagination links --}}
                @include('cms.layouts.pagination.pagination-links')
            </div>
        </div>

        @include('cms.layouts.footers.auth')
    </div>
@endsection

@can('admins-block')
    @push('script')
        <script type="text/javascript">
        // block using fetch api
            function block(id) {
              fetch("{{ route('admin.' . $page_info['link'] . '.block') }}", {
                method: "POST",
                headers: {
                  "Content-Type": "application/json",
                  "Accept": "application/json, text-plain, */*",
                  "X-Requested-With": "XMLHttpRequest",
                  "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                  id: id
                })
              }).then((data) => {
                console.log(data);
                location.reload();
              }).catch((error) => {
                console.log(error);
              });
            }
        </script>
    @endpush
@endcan
