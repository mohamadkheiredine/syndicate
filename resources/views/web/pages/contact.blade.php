@extends('web.layouts.main')

@section('content')
<div class="page-header wow animated fadeInDown" data-wow-duration="0.5s">
    <div class="q-container">
        <div class="q-row">
            <div class="q-col-1-1">
                <h1 class="section-headline"><span>Get Involved</span> Contact Mobile Operators Syndicate</h1>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="q-container">
        <div class="q-row">
            <div class="content q-col-2-3 wow animated fadeIn" data-wow-delay="0.2s" data-wow-duration="0.5s">
                <p>&nbsp;</p>
                <p>Have something to share, ideas, suggestions or a simple idea?</p>
                <p>Mobile Operators Syndicate would love to hear from you!</p>

                @if(session('contact_status'))
                <p class="{{ session('contact_status_type') === 'success' ? 'groupGreen' : 'groupRed' }}" align="center" style="color:#000;">{{ session('contact_status') }}</p>
                @endif

                <form method="post" action="{{ route('web.contact.send') }}" class="note">
                    @csrf

                    <div class="q-row">
                        <div class="q-col-1-2">
                            <label for="name">Your name? <span class="required">*</span></label><br>
                            <input type="text" name="name" id="name" class="full-width" value="{{ old('name') }}">
                            @error('name')<small style="color:#F00;">{{ $message }}</small>@enderror
                        </div>
                        <div class="q-col-1-2">
                            <label for="email">Your e-mail? <span class="required">*</span></label><br>
                            <input type="text" name="email" id="email" class="full-width" value="{{ old('email') }}">
                            @error('email')<small style="color:#F00;">{{ $message }}</small>@enderror
                        </div>
                    </div>
                    <div class="q-row">
                        <div class="q-col-1-1">
                            <label for="message">Your message<span class="required">*</span></label><br>
                            <textarea name="message" id="message" class="full-width" rows="4">{{ old('message') }}</textarea>
                            @error('message')<small style="color:#F00;">{{ $message }}</small>@enderror
                        </div>
                    </div>
                    <div class="q-row">
                        <div class="q-col-1-1">
                            <button type="submit" id="submit-contact" class="button button-submit"><i class="fa fa-envelope"></i> Submit your message</button>
                        </div>
                    </div>
                </form>
            </div>

            @include('web.layouts.sidebar.right-panel')
        </div>
    </div>
</div>
@endsection
