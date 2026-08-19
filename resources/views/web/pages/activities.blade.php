@extends('web.layouts.main')

@section('content')
<div class="page-header wow animated fadeInDown" data-wow-duration="0.5s">
    <div class="q-container">
        <div class="q-row">
            <div class="q-col-1-1">
                <h1 class="section-headline"><span>News</span> Special News from the Syndicate</h1>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="q-container">
        <div class="q-row">
            <div class="content q-col-1-1 wow animated fadeIn" data-wow-delay="0.2s" data-wow-duration="0.5s">
                <p class="big-text"><em>The most recent news and informations important notice to you will be written in this page</em></p>

                <div class="roadmap-archive">
                    <div class="roadmap-archive-header q-row">
                        <div class="q-col-2-3">
                            <h2 class="section-headline">Latest Activities</h2>
                        </div>
                    </div>

                    @forelse($activities as $index => $activity)
                    <div class="roadmap-items q-clear">
                        <div class="roadmap-item">
                            <time date="{{ $activity->post_date ? $activity->post_date->format('Y-m-d') : '' }}">{{ $index + 1 }}<span>{{ $activity->post_date ? $activity->post_date->format('d.m.Y') : '' }}</span></time>
                            <div class="item-body">
                                <div class="q-row">
                                    <div class="q-col-1-2">
                                        <h3 class="item-title"><a href="{{ route('web.activities.show', $activity->id) }}">{{ ucfirst($activity->title) }}</a></h3>
                                        <span class="small-text"><i class="fa fa-map-marker"></i>{{ ucfirst($activity->place) }}</span>
                                        <p>{{ ucfirst($activity->short_description) }}</p>
                                    </div>
                                    <div class="q-col-1-2">
                                        <div class="article-image">
                                            @if($activity->main_image)
                                            <a href="{{ route('web.activities.show', $activity->id) }}">
                                                <img src="{{ $activity->main_image }}">
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
