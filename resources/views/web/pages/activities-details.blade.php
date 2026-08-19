@extends('web.layouts.main')

@section('content')
<div class="page-header wow animated fadeInDown" data-wow-duration="0.5s">
    <div class="q-container">
        <div class="q-row">
            <div class="q-col-1-1">
                <h2 class="section-headline"><span>Syndicate</span> Activities Details</h2>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="q-container">
        <div class="q-row">
            <div class="content q-col-2-3 wow animated fadeIn" data-wow-delay="0.2s" data-wow-duration="0.5s">
                @if($activity)
                <article class="blog-post">
                    <header class="blog-post-header">
                        <h1 class="blog-post-headline">{{ ucfirst($activity->title) }}</h1>
                        <div class="blog-post-info">Place <a href="javascript:void(0);">{{ ucfirst($activity->place) }}</a></div>
                    </header>

                    @if($activity->main_image || $activity->gallery->count())
                    <div class="demo-slider-3">
                        <div class="simple-slider">
                            @if($activity->main_image)
                            <div class="simple-slide"><img src="{{ $activity->main_image }}" alt=""></div>
                            @endif
                            @foreach($activity->gallery as $gal)
                            <div class="simple-slide"><img src="{{ $gal->gallery_image }}" alt=""></div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="blog-post-content">
                        {!! $activity->description !!}
                    </div>

                    <div style="clear:both;"></div>

                    @if($activity->any_file)
                    @php $ext = strtolower(pathinfo($activity->getAttributes()['any_file'], PATHINFO_EXTENSION)); @endphp
                    Associated Documents&nbsp;
                    <a href="{{ $activity->any_file }}" target="_blank">
                        <i class="fa {{ in_array($ext, ['doc', 'docx']) ? 'fa-file-word-o' : 'fa-file-pdf-o' }} fa-2x"></i>
                    </a>
                    @endif
                </article>
                @else
                <font color="red">Data not found</font>
                @endif
            </div>

            @include('web.layouts.sidebar.right-panel')
        </div>
    </div>
</div>

@if($activity && ($activity->main_image || $activity->gallery->count()))
@push('scripts')
<script src="{{ asset('assets-web/libraries/owl-carousel/owl.carousel.min.js') }}"></script>
@endpush
@endif
@endsection
