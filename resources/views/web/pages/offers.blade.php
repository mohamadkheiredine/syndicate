@extends('web.layouts.main')

@section('content')
<div class="page-header wow animated fadeInDown" data-wow-duration="0.5s">
    <div class="q-container">
        <div class="q-row">
            <div class="q-col-1-1">
                <h1 class="section-headline"><span>Offers</span> Where to find special offers from the Syndicate</h1>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="q-container">
        <div class="q-row">
            <div class="content q-col-1-1 wow animated fadeIn" data-wow-delay="0.2s" data-wow-duration="0.5s">
                <p class="big-text"><em>To make things more joyfull at the syndicate we will help you find specials that makes your life easier find the list bellow</em></p>

                <div class="roadmap-archive">
                    <div class="roadmap-archive-header q-row">
                        <div class="q-col-2-3">
                            <h2 class="section-headline">Latest Offers</h2>
                        </div>
                    </div>

                    @forelse($offers as $index => $offer)
                    <div class="roadmap-items q-clear">
                        <div class="roadmap-item">
                            <time date="{{ $offer->start_date ? $offer->start_date->format('Y-m-d') : '' }}">{{ $index + 1 }}<span>{{ $offer->start_date ? $offer->start_date->format('d.m.Y') : '' }}</span><span class="hour">To<span>{{ $offer->end_date ? $offer->end_date->format('d.m.Y') : '' }}</span></span></time>
                            <div class="item-body">
                                <div class="q-row">
                                    <div class="q-col-1-2">
                                        <h3 class="item-title"><a href="{{ route('web.offers.show', $offer->id) }}">{{ ucfirst($offer->title) }}</a></h3>
                                        <span class="small-text"><i class="fa fa-map-marker"></i>{{ ucfirst($offer->place) }}</span>
                                        <p>{{ ucfirst($offer->short_description) }}</p>
                                        <p><a class="button button-outline">{{ $offer->offers }} <i class="fa fa-caret-right icon-on-right"></i></a></p>
                                    </div>
                                    <div class="q-col-1-2">
                                        <div class="article-image">
                                            @if($offer->display_image)
                                            <a href="{{ route('web.offers.show', $offer->id) }}">
                                                <img src="{{ $offer->display_image }}">
                                            </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <font color="red">Data not found</font>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="q-row"></div>
    </div>
</div>
@endsection
