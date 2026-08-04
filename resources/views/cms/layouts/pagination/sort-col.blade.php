<th scope="col">
    <a href="{{ request()->fullUrlWithQuery(['sort' => $col_name , 'order' => request('order') == 'asc' ? 'desc' : 'asc']) }}">
        {{$col_title}}
        @if (request('sort') == 'name')
            <i class="fas fa-sort-{{ request('order') == 'asc' ? 'up' : 'down' }}"></i>
        @else
            <i class="fas fa-sort"></i>
        @endif
    </a>
</th>