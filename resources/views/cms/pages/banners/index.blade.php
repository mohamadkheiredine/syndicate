@extends('cms.layouts.main')

@section('content')
@include('cms.layouts.headers.cards')

<div class="container-fluid mt--7">
    <div class="card shadow mt-5">
        {{-- include data table header --}}
        @include('cms.layouts.pagination.datatable-header', ['columns' => []])

        @include('cms.components.alert', ['with' => 'status', 'bg' => 'success', 'class' => 'mx-4'])

        <div class="table-responsive">
        <table class="table align-items-center table-flush">
            <thead class="thead-light">
                <tr>
                    <th scope="col" class="no-sort">Image</th>
                    <th scope="col" class="no-sort">Published</th>
                    <th scope="col" class="no-sort"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $row)
                <tr>
                    <td>
                        @if($row->main_image)
                        <img src="{{ $row->main_image }}" width="160" class="img-thumbnail">
                        @endif
                    </td>
                    <td class="adjust-element">
                        <label class="custom-toggle mb-0">
                            <input
                                @can('banners-publish') onclick="togglePublish({{ $row->id }})" @else disabled @endcan
                                class="publish-toggle-js" type="checkbox" value="{{ $row->id }}"
                                @if((int) $row->publish_status === 1) checked @endif>
                            <span class="custom-toggle-slider rounded-circle"></span>
                        </label>
                    </td>
                    <td class="text-right">
                        <div class="dropdown">
                            <a class="btn btn-sm btn-icon-only text-light" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                @can('banners-edit')
                                <a class="dropdown-item" href="{{ route('admin.'.$page_info['link'].'.edit', $row) }}">Edit</a>
                                @endcan

                                @can('banners-delete')
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

@can('banners-publish')
@push('script')
<script type="text/javascript">
    // toggle publish using fetch api
    function togglePublish(id) {
        fetch("{{ url('admin/'.$page_info['link']) }}/" + id + "/toggle-publish", {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json, text-plain, */*",
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            }
        }).then((data) => {
            location.reload();
        }).catch((error) => {
            console.log(error);
        });
    }
</script>
@endpush
@endcan
