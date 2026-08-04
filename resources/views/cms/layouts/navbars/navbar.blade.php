@if(Auth::guard('admin')->check())
    @include('cms.layouts.navbars.navs.auth')
@endif