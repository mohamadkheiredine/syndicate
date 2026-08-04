<div class="mt-4">
  <!-- Filter Button -->
  <button type="button" class="btn btn-sm btn-primary " data-toggle="modal" data-target="#filterModal">
      Filter
  </button>

  <button onclick="clearURLParams()" class="btn btn-sm btn-secondary">
    <i class="fas fa-sync-alt"></i> Reset Filters
  </button>
</div>


<div class="d-flex justify-content-center mt-3">

  {{-- Search Box --}}

  <form id="search-form" class="mt-2 mr-2" method="GET" action="{{url()->current()}}">
        @foreach ($_GET as $key => $value)
          @unless ($key === 'search' or $key === 'page')
            <input type="hidden" name="{{ htmlspecialchars($key) }}" value="{{ htmlspecialchars($value) }}" />
          @endunless
        @endforeach
      <div class="input-group mb-3">
        <input type="text" name="search" id="search-input" class="form-control form-control-sm" placeholder="Search" value="{{ Request::get('search') }}">
        <div class="input-group-append">
            <button class="btn btn-sm btn-primary" type="submit"">Search</button>
        </div>
      </div>
  </form>


</div>


<!-- Filter Modal -->
<div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterModalLabel">Filter Records</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ url()->current() }}" method="GET">
                    @foreach ($_GET as $key => $value)
                      @unless (in_array($key, $columns) or $key === 'page')
                        <input type="hidden" name="{{ htmlspecialchars($key) }}" value="{{ htmlspecialchars($value) }}" />
                      @endunless
                    @endforeach

                    @foreach($columns as $columnTitle => [$column, $type])
                        <div class="form-group">
                            <label for="{{ $column }}" class="float-left">{{ $columnTitle }}</label>
                            @if($type === 'checkbox')
                                <input type="checkbox" class="form-check-input" id="{{ $column }}" name="{{ $column }}" value="1" {{ Request::get($column) ? 'checked' : '' }}>
                            @elseif($type === 'boolean')
                                <select class="form-control" id="{{ $column }}" name="{{ $column }}">
                                    <option value="">All</option>
                                    <option value="true" {{ Request::get($column) === 'true' ? 'selected' : '' }}>True</option>
                                    <option value="false" {{ Request::get($column) === 'false' ? 'selected' : '' }}>False</option>
                                </select>
                            @else
                                <input type="{{ $type }}" class="form-control" id="{{ $column }}" name="{{ $column }}" value="{{ Request::get($column) }}">
                            @endif
                        </div>
                    @endforeach
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Apply Filter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<style>
  .filter-button {
    font-size: 18px;
    height: calc(1.5em + 0.75rem + 2px); /* Adjust the height to match the search box */
    padding: 0.375rem 0.75rem; /* Adjust the padding as needed */
  }
</style>


<script>
  function clearURLParams() {
    // Remove the query string part from the current URL
    var cleanURL = window.location.href.split('?')[0];

    // Replace the current URL with the clean URL and reload the page
    window.location.href = cleanURL;
  }
</script>
