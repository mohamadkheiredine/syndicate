@extends('web.layouts.main')

@section('content')
<div class="page-header wow animated fadeInDown" data-wow-duration="0.5s">
    <div class="q-container">
        <div class="q-row">
            <div class="q-col-1-1">
                <h1 class="section-headline"><span>Get Involved</span> Fill your Syndicate card online</h1>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="q-container">
        <div class="q-row">
            <div class="content q-col-2-3 wow animated fadeIn" data-wow-delay="0.2s" data-wow-duration="0.5s">
                <p class="big-text"><em>When <strong>our future is at stake</strong> we have a responsibility to do our best to help you participate.</em></p>

                <form action="{{ route('web.login') }}" name="log" id="log" method="post" class="note">
                    @csrf

                    <p>By filling up below you're taking real steps toward the syndicate partcipation you will receive: <strong>Latest Offers</strong>, <strong>Syndicate Invitations</strong> and <strong>Special Participation</strong>!</p>

                    <h3>Login:</h3>
                    @if($errors->any())
                    <div class="groupRed" align="center" style="color:#000;">{{ $errors->first() }}</div>
                    @endif
                    @if(session('activated'))
                    <div class="groupGreen" align="center" style="color:#000;">Your account has been activated. You can now log in.</div>
                    @endif

                    <div class="q-row">
                        <div class="q-col-1-2">
                            <p><input type="text" placeholder="Email address" name="email" value="{{ old('email') }}" id="email" class="full-width"></p>
                        </div>
                        <div class="q-col-1-2">
                            <p><input type="password" placeholder="Password" name="password" id="password" class="full-width"></p>
                        </div>
                    </div>
                    <p><input type="submit" name="login" value="Login"></p>
                </form>
            </div>

            @include('web.layouts.sidebar.right-panel')
        </div>
    </div>
</div>
@endsection
