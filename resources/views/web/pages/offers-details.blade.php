@extends('web.layouts.main')

@section('content')
<div class="page-header wow animated fadeInDown" data-wow-duration="0.5s">
    <div class="q-container">
        <div class="q-row">
            <div class="q-col-1-1">
                <h2 class="section-headline"><span>Offers</span> Offers Details</h2>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="q-container">
        <div class="q-row">
            <div class="content q-col-2-3 wow animated fadeIn" data-wow-delay="0.2s" data-wow-duration="0.5s">
                @if($offer)
                <article class="blog-post">
                    <header class="blog-post-header">
                        <h1 class="blog-post-headline">{{ ucfirst($offer->title) }}</h1>
                        <div class="blog-post-info">Place <a href="javascript:void(0);">{{ ucfirst($offer->place) }}</a></div>
                    </header>

                    @if($offer->display_image)
                    <div class="blog-post-image">
                        <img src="{{ $offer->display_image }}" alt="" width="960" height="540">
                    </div>
                    @endif

                    <div class="blog-post-content">
                        {!! $offer->description !!}

                        @if($offer->pdf)
                        <br>
                        <a href="{{ $offer->pdf }}">Download PDF</a>
                        @endif
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
