<p class="text-muted mb-0">{{ $rows->total() }} entries</p>
<div class="mt-4">
    <form action="{{ request()->fullUrlWithQuery([]) }}" method="GET" class="form-inline">
        @foreach ($_GET as $key => $value)
            @unless ($key === 'entries_per_page')
                <input type="hidden" name="{{ htmlspecialchars($key) }}" value="{{ htmlspecialchars($value) }}" />
            @endunless
        @endforeach
        <label for="entries_per_page" class="mr-2">Show:</label>
        <select name="entries_per_page" id="entries_per_page" class="form-control form-control-sm"
            onchange="this.form.submit()">
            <option value="10"{{ Request::get('entries_per_page', 10) == 10 ? ' selected' : '' }}>10</option>
            <option value="25"{{ Request::get('entries_per_page') == 25 ? ' selected' : '' }}>25</option>
            <option value="50"{{ Request::get('entries_per_page') == 50 ? ' selected' : '' }}>50</option>
            <option value="100"{{ Request::get('entries_per_page') == 100 ? ' selected' : '' }}>100</option>
        </select>
        <span class="ml-2">entries</span>
    </form>
</div>
