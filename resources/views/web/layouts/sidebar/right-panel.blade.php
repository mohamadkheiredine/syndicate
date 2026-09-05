<aside id="sidebar" class="q-col-1-3 wow animated fadeIn" data-wow-delay="0.4s" data-wow-duration="0.5s">
    <div class="widget box box-alt">
        <div id="scrollerota">
            <ul class="images">
                @foreach($right_up_ads as $ad)
                <li><img src="{{ $ad->display_image }}" alt=""></li>
                @endforeach
            </ul>
            <ul class="text"></ul>
        </div>
    </div>

    <div class="widget box box-alt">
        <div id="scrollerota_down">
            <ul class="images">
                @foreach($right_down_ads as $ad)
                <li><img src="{{ $ad->display_image }}" alt=""></li>
                @endforeach
            </ul>
            <ul class="text"></ul>
        </div>
    </div>

    <div class="widget box box-alt">
        <h4 class="box-headline"><a href="#">Join syndicate's List!</a></h4>
        <p>Receive latest offers and promotions news and events!"</p>
        <div id="message"></div>
        <form name="join_syndicate" method="post" action="{{ route('web.join-syndicate') }}#response" onsubmit="return subscriber()">
            @csrf
            <span id="response" style="color:#F00; font-size:14px;">
                @if(session('join_status'))
                {{ session('join_status') }}
                @endif
            </span>
            <input type="text" placeholder="Your name" name="syndicate_name" id="syndicate_name">
            <input type="text" placeholder="Your email" name="syndicate_email" id="syndicate_email">
            <input type="submit" name="join" value="I want to join">
        </form>
    </div>
</aside>

@push('scripts')
<script type="text/javascript" src="{{ asset('assets-web/libraries/scrollerota/jquery.scrollerotaup.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets-web/libraries/scrollerota/jquery.scrollerotadown.min.js') }}"></script>
<script type="text/javascript">
    // Old's own two plugin files default to different timer values (top:
    // 5000ms, bottom: 4000ms) and old's real init code never overrides
    // them, so they drift apart over time in old's live site too. Passing
    // a matching timer here keeps them in sync, per explicit request.
    $("#scrollerota").scrollerota({ timer: 5000 });
    $("#scrollerota_down").scrollerotadown({ timer: 5000 });

    function subscriber() {
        if (document.getElementById("syndicate_name").value == "") {
            document.getElementById("syndicate_name").focus();
            return false;
        }
        var email = document.getElementById("syndicate_email");
        if (email.value == "") {
            email.focus();
            return false;
        }
        var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if (!filter.test(email.value)) {
            email.focus();
            return false;
        }
    }
</script>
@endpush
