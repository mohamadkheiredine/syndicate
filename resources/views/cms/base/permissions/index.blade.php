@extends('cms.layouts.main')

@section('content')
    @include('cms.layouts.headers.cards')

    <div class="container-fluid mt--7">
        <div class="card shadow mt-5">
            {{-- include data table header --}}
            @include('cms.layouts.pagination.datatable-header', ['columns' => $filterableColumns])

            @include('cms.components.alert', ['with' => 'status', 'bg' => 'success', 'class' => 'mx-4'])

            <div class="table-responsive">
                <div class="table-responsive">
<table class="table align-items-center table-flush">
                    <thead class="thead-light">
                        <tr>
                            @include('cms.layouts.pagination.sort-col', ['col_name' => 'name', 'col_title' => 'Name'])
                            <th scope="col" class="no-sort"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rows as $row)
                            <tr>
                                <td>{{ $row->name }}</td>
                                <td class="text-right">
                                    <div class="dropdown">
                                        <a class="btn btn-sm btn-icon-only text-light" role="button" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                            @can('permissions-edit')
                                                <a class="dropdown-item"
                                                    href="{{ route('admin.' . $page_info['link'] . '.edit', $row) }}">Edit</a>
                                            @endcan

                                            @can('permissions-delete')
                                                <form action="{{ route('admin.' . $page_info['link'] . '.destroy', $row) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('delete')

                                                    <button type="button" class="dropdown-item"
                                                        onclick="confirm('Are you sure you want to delete this record?') ? this.parentElement.submit() : ''">
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

        </div>

        @include('cms.layouts.footers.auth')
    </div>
@endsection
