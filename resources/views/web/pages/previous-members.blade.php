@extends('web.layouts.main')

@section('content')
<div class="page-header wow animated fadeInDown" data-wow-duration="0.5s">
    <div class="q-container">
        <div class="q-row">
            <div class="q-col-1-1">
                <h1 class="section-headline"><span>About Us</span> Mobile Operators Syndicate</h1>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="q-container">
        <div class="q-row">
            <div class="content q-col-2-3 wow animated fadeIn" data-wow-delay="0.2s" data-wow-duration="0.5s">
                <h2><a href="javascript:void(0);">Meet Syndicate's Family {{ $prev_year }}</a></h2>
                <div class="q-row">
                    @forelse($members as $member)
                    <div class="q-col-1-3">
                        <div class="team-member">
                            @if($member->main_image)
                            <img src="{{ $member->main_image }}" alt="">
                            @endif
                            <p>
                                <strong>{{ ucfirst(stripslashes($member->name)) }}</strong><br>
                                <em class="small-text">{{ ucfirst(stripslashes($member->designation)) }}</em>
                            </p>
                        </div>
                    </div>
                    @empty
                    <font color="red">Data not found</font>
                    @endforelse
                </div>
            </div>

            @include('web.layouts.sidebar.right-panel')
        </div>
    </div>
</div>
@endsection
