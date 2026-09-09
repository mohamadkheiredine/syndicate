@extends('web.layouts.main')

@section('content')
<div class="page-header wow animated fadeInDown" data-wow-duration="0.5s">
    <div class="q-container">
        <div class="q-row">
            <div class="q-col-1-1">
                <h1 class="section-headline"><span>Terms</span>Rules and Regulations</h1>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="q-container">
        <div class="q-row">
            <div class="content q-col-1-1 wow animated fadeIn" data-wow-delay="0.2s" data-wow-duration="0.5s">

                <h2>&nbsp;</h2>
                <h2>Rules and Regulations</h2>
                @if($sections->isNotEmpty())
                <div id="demo-tabs-vert" class="tabs-vert">
                    <ul class="resp-tabs-list">
                        @foreach($sections as $section)
                        <li>{{ $section['label'] }}</li>
                        @endforeach
                    </ul>
                    <div class="resp-tabs-container">
                        @foreach($sections as $section)
                        <div>
                            {!! $section['description'] !!}
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Load the tab widget plugin on this page; main.js initialises every
     .tabs-vert / .tabs-hor once it is present. --}}
<script src="{{ asset('assets-web/libraries/easy-responsive-tabs/easyresponsivetabs.js') }}"></script>
@endpush
