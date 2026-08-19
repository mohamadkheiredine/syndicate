@extends('web.layouts.main')

@section('content')
<div class="page-header wow animated fadeInDown" data-wow-duration="0.5s">
    <div class="q-container">
        <div class="q-row">
            <div class="q-col-1-1">
                <h2 class="section-headline"><span>Media</span> News Details</h2>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="q-container">
        <div class="q-row">
            <div class="content q-col-2-3 wow animated fadeIn" data-wow-delay="0.2s" data-wow-duration="0.5s">
                @if($newsItem)
                <article class="blog-post">
                    <header class="blog-post-header">
                        <h1 class="blog-post-headline">{{ ucfirst($newsItem->title) }}</h1>
                        <div class="blog-post-info">
                            @if($newsItem->what_type)
                            Published in <a href="javascript:void(0);">{{ ucfirst($newsItem->what_type) }}</a>
                            @endif
                            on <time>{{ $newsItem->post_date ? $newsItem->post_date->format('d.m.Y') : '' }}</time>.
                        </div>
                    </header>

                    @if($newsItem->main_image)
                    <div class="blog-post-image"><img src="{{ $newsItem->main_image }}" alt=""></div>
                    @endif

                    <div class="blog-post-content">
                        {!! $newsItem->description !!}
                    </div>
                </article>
                @else
                <font color="red">Data not found</font>
                @endif
            </div>

            @include('web.layouts.sidebar.right-panel')
        </div>
    </div>
</div>
@endsection
