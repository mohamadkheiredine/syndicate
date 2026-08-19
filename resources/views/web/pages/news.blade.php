@extends('web.layouts.main')

@section('content')
<div class="page-header wow animated fadeInDown" data-wow-duration="0.5s">
    <div class="q-container">
        <div class="q-row">
            <div class="q-col-1-1">
                <h1 class="section-headline"><span>Media</span> Syndicate's News</h1>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="q-container">
        <div class="q-row">
            <div class="content q-col-2-3 wow animated fadeIn" data-wow-delay="0.2s" data-wow-duration="0.5s">
                <div class="blog-archive">
                    @forelse($news as $item)
                    <article class="blog-post">
                        <header class="blog-post-header">
                            <h2 class="blog-post-headline"><a href="{{ route('web.news.show', $item->id) }}">{{ ucfirst($item->title) }}</a></h2>
                            <div class="blog-post-info">Published
                                @if($item->what_type)
                                in <a href="javascript:void(0);">{{ ucfirst($item->what_type) }}</a>
                                @endif
                                on {{ $item->post_date ? $item->post_date->format('d.m.Y') : '' }}</div>
                        </header>

                        @if($item->main_image)
                        <div class="blog-post-image">
                            <a href="{{ route('web.news.show', $item->id) }}">
                                <img src="{{ $item->main_image }}" alt="">
                            </a>
                        </div>
                        @endif

                        <div class="blog-post-excerpt">
                            <p>{{ ucfirst($item->short_description) }}</p>
                            <p><a href="{{ route('web.news.show', $item->id) }}" class="button">Continue reading</a></p>
                        </div>
                    </article>
                    @empty
                    <font color="red">Data not found</font>
                    @endforelse

                    @if($news->hasPages())
                    <div class="pagination">
                        @if($news->currentPage() > 1)
                        <a href="{{ $news->previousPageUrl() }}" class="prev page-numbers"><i class="fa fa-caret-left"></i></a>
                        @endif

                        @for($p = 1; $p <= $news->lastPage(); $p++)
                        @if($p == $news->currentPage())
                        <span class="page-numbers current">{{ $p }}</span>
                        @else
                        <a href="{{ $news->url($p) }}" class="page-numbers">{{ $p }}</a>
                        @endif
                        @endfor

                        @if($news->currentPage() < $news->lastPage())
                        <a href="{{ $news->nextPageUrl() }}" class="next page-numbers"><i class="fa fa-caret-right"></i></a>
                        @endif
                    </div>
                    @endif
                </div>
            </div>

            @include('web.layouts.sidebar.right-panel')
        </div>
    </div>
</div>
@endsection
