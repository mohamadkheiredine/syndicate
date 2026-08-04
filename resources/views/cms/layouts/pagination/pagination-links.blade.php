{{-- <div class="card-footer">
      <div class="row mt-3">
        <div class="col">
            <p class="text-muted">
                Showing {{ $rows->firstItem() }} to {{ $rows->lastItem() }} of {{ $rows->total() }} entries
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <nav>
                <ul class="pagination justify-content-center">
                    <li class="page-item{{ $rows->onFirstPage() ? ' disabled' : '' }}">
                        <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $rows->currentPage()-1]) }}" tabindex="-1" aria-disabled="{{ $rows->onFirstPage() ? 'true' : 'false' }}">
                            Previous
                        </a>
                    </li>
                    @foreach ($rows->getUrlRange(1, $rows->lastPage()) as $page => $url)
                        <li class="page-item{{ $page == $rows->currentPage() ? ' active' : '' }}">
                            <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $page]) }}">{{ $page }}</a>
                        </li>
                    @endforeach
                    <li class="page-item{{ $rows->currentPage() == $rows->lastPage() ? ' disabled' : '' }}">
                        <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $rows->currentPage()+1 ]) }}" aria-disabled="{{ $rows->currentPage() == $rows->lastPage() ? 'true' : 'false' }}">
                            Next
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div> --}}


<div class="card-footer">
    <div class="row mt-3">
        <div class="col">
            <p class="text-muted">
                Showing {{ $rows->firstItem() }} to {{ $rows->lastItem() }} of {{ $rows->total() }} entries
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <nav>
                <ul class="pagination justify-content-center">
                    <li class="page-item{{ $rows->onFirstPage() ? ' disabled' : '' }}">
                        <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $rows->currentPage()-1]) }}" tabindex="-1" aria-disabled="{{ $rows->onFirstPage() ? 'true' : 'false' }}">
                            Previous
                        </a>
                    </li>
                    @php
                        // Determine the range of links to display
                        $startRange = max($rows->currentPage() - 2, 1);
                        $endRange = min($rows->currentPage() + 2, $rows->lastPage());
                    @endphp
                    @if($startRange > 1)
                        <li class="page-item">
                            <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => 1]) }}">1</a>
                        </li>
                        @if($startRange > 2)
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                        @endif
                    @endif
                    @for($i = $startRange; $i <= $endRange; $i++)
                        <li class="page-item{{ $i == $rows->currentPage() ? ' active' : '' }}">
                            <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $i]) }}">{{ $i }}</a>
                        </li>
                    @endfor
                    @if($endRange < $rows->lastPage())
                        @if($endRange < ($rows->lastPage() - 1))
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                        @endif
                        <li class="page-item">
                            <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $rows->lastPage()]) }}">{{ $rows->lastPage() }}</a>
                        </li>
                    @endif
                    <li class="page-item{{ $rows->currentPage() == $rows->lastPage() ? ' disabled' : '' }}">
                        <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $rows->currentPage()+1 ]) }}" aria-disabled="{{ $rows->currentPage() == $rows->lastPage() ? 'true' : 'false' }}">
                            Next
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>
