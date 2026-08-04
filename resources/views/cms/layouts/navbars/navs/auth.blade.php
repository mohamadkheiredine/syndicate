<nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
    <div class="container-fluid">
        <a href="{{ url('/') }}" class="h4 mb-0 text-white text-uppercase d-none d-md-inline-block" target="_blank"><i class="fas fa-globe-americas mr-2"></i>Visit Website</a>
        <ul class="navbar-nav align-items-center d-none d-md-flex">
            <li class="nav-item">
                <form class="my-auto" id="timezone-form" action="{{ route('admin.admins.update-timezone') }}" method="POST">
                    @csrf
                    <div class="form-group mb-0">
                        <select name="timezone" id="timezone" class="chosen-select" onchange="document.getElementById('timezone-form').submit()">
                            @foreach (timezone_identifiers_list() as $timezone)
                                <option value="{{ $timezone }}"{{ $timezone == request()->user()->timezone ? ' selected' : '' }}>{{ $timezone }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="media align-items-center">
                        <span class="avatar avatar-sm rounded-circle">
                            <img src="{{ asset('assets-cms/images/default_avatar.png') }}" alt="Profile Picture">
                        </span>
                        <div class="media-body ml-2 d-none d-lg-block">
                            <span class="mb-0 text-sm font-weight-bold">{{ Auth::guard('admin')->user()->first_name . ' ' . Auth::guard('admin')->user()->last_name }}</span>
                        </div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                    <div class="dropdown-header noti-title">
                        <h6 class="text-overflow m-0">Welcome!</h6>
                    </div>
                    <a href="{{ route('admin.profile.edit') }}" class="dropdown-item">
                        <i class="fas fa-user-circle"></i>
                        <span>My profile</span>
                    </a>
                    @can('cms_settings-edit')
                    <a href="{{ route('admin.cms-settings.edit') }}" class="dropdown-item">
                        <i class="fas fa-cog"></i>
                        <span>CMS Settings</span>
                    </a>
                    @endif
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item cursor-pointer" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </li>
        </ul>
    </div>
</nav>

<!-- Include Chosen stylesheet -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">

<!-- Include jQuery and Chosen script -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>

<style>
    .chosen-select {
        width: 200px;
        font-size: 10px;
    }

    .chosen-container .chosen-results {
        max-height: 200px;
        overflow-y: auto;
    }
</style>
<script>
    $(document).ready(function() {
        $('.chosen-select').chosen({
            // width: '100%',
            width: '150px',
            disable_search_threshold: 10,
            no_results_text: 'No results found',
            search_contains: true
        });
    });
</script>