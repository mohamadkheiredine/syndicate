<nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">
    <div class="container-fluid">
        {{-- Toggler --}}
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        {{-- Brand --}}
        <a class="navbar-brand pt-0" href="{{ route('admin.dashboard') }}">
            <img src="{{ $cms_logo ? $cms_logo : asset('assets-cms/images/logo.png') }}" class="navbar-brand-img" alt="Logo">
        </a>
        {{-- User --}}
        <ul class="nav align-items-center d-md-none">
            <li class="nav-item dropdown">
                <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="media align-items-center">
                        <span class="avatar avatar-sm rounded-circle">
                            <img alt="Image placeholder" src="{{ asset('assets-cms/images/default_avatar.png') }}">
                        </span>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                    <div class=" dropdown-header noti-title">
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
                    <a class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="ni ni-user-run"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </li>
        </ul>
        {{-- Collapse --}}
        <div class="collapse navbar-collapse" id="sidenav-collapse-main">
            {{-- Collapse header --}}
            <div class="navbar-collapse-header d-md-none">
                <div class="row">
                    <div class="col-6 collapse-brand">
                        <a href="{{ route('admin.dashboard') }}">
                            <img src="{{ asset('assets-cms/images/logo.png') }}">
                        </a>
                    </div>
                    <div class="col-6 collapse-close">
                        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle sidenav">
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                </div>
            </div>
            {{-- Navigation --}}
            @canany(['dashboard-view', 'admins-view', 'roles-view'])
            <ul class="navbar-nav">
                @can('dashboard-view')
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-drafting-compass text-primary"></i>Dashboard
                    </a>
                </li>
                @endcan
                @can('admins-view')
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.admins.*') ? 'active' : '' }}" href="{{ route('admin.admins.index') }}">
                        <i class="fas fa-users-cog text-primary"></i>Admins Management
                    </a>
                </li>
                @endcan
                <li class="nav-item">
                    <a class="nav-link" href="#roles-permissions" data-toggle="collapse" role="button" aria-expanded="{{ Route::is('admin.roles.*') || Route::is('admin.permissions.*') ? 'true' : 'false' }}" aria-controls="roles-permissions">
                        <i class="fas fa-user-shield text-primary"></i>
                        <span class="nav-link-text">Roles & Permissions</span>
                    </a>
                    <div class="collapse {{ Route::is('admin.roles.*') || Route::is('admin.permissions.*') ? 'show' : '' }}" id="roles-permissions">
                        <ul class="nav nav-sm flex-column">
                            @can('roles-view')
                            <li class="nav-item">
                                <a class="nav-link {{ Route::is('admin.roles.*') ? 'active' : '' }}" href="{{ route('admin.roles.index') }}">
                                    Roles
                                </a>
                            </li>
                            @endcan
                            @can('permissions-view')
                            <li class="nav-item">
                                <a class="nav-link {{ Route::is('admin.permissions.*') ? 'active' : '' }}" href="{{ route('admin.permissions.index') }}">
                                    Permissions
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </div>
                </li>
                @can('simulation-create')
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.simulation.*') ? 'active' : '' }}" href="{{ route('admin.simulation.index') }}">
                        <i class="fas fa-user-secret text-primary"></i>Simulation
                    </a>
                </li>
                @endcan
            </ul>

            <hr class="my-3">
            @endcanany

            <h6 class="navbar-heading text-muted">USERS MANAGEMENT</h6>
            <ul class="navbar-nav mb-3">
                @can('users-view')
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                        <i class="fas fa-users"></i>Users Management
                    </a>
                </li>
                @endcan
                @can('syndicate_users-view')
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.syndicate-users.*') ? 'active' : '' }}" href="{{ route('admin.syndicate-users.index') }}">
                        <i class="fas fa-id-card"></i>Syndicate Users
                    </a>
                </li>
                @endcan
                @can('members_payment-view')
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.members-payment.*') ? 'active' : '' }}" href="{{ route('admin.members-payment.index') }}">
                        <i class="fas fa-hand-holding-usd"></i>Members Payment
                    </a>
                </li>
                @endcan
            </ul>

            <h6 class="navbar-heading text-muted">CONTENT MANAGEMENT</h6>
            <ul class="navbar-nav mb-3">
                @can('faqs-view')
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.faqs.*') ? 'active' : '' }}" href="{{ route('admin.faqs.index') }}">
                        <i class="fas fa-question-circle"></i>Faqs
                    </a>
                </li>
                @endcan
            </ul>

            <h6 class="navbar-heading text-muted">FORMS & SUBMISSIONS</h6>
            <ul class="navbar-nav mb-3">
                @can('support_categories-view')
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.support-categories.*') ? 'active' : '' }}" href="{{ route('admin.support-categories.index') }}">
                        <i class="fas fa-life-ring"></i>Support Categories
                    </a>
                </li>
                @endcan
                @can('support-view')
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.support.*') ? 'active' : '' }}" href="{{ route('admin.support.index') }}">
                        <i class="fas fa-envelope"></i>Contact Forms
                    </a>
                </li>
                @endcan
            </ul>

            @canany(['fixed_sections-view', 'social_media-view', 'terms_and_conditions-edit', 'privacy_policy-edit'])
            <h6 class="navbar-heading text-muted">GENERAL MANAGEMENT</h6>
            <ul class="navbar-nav mb-3">
                @can('fixed_sections-view')
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.fixed-sections.*') ? 'active' : '' }}" href="{{ route('admin.fixed-sections.index') }}">
                        <i class="fas fa-align-center"></i>Fixed Sections
                    </a>
                </li>
                @endcan
                @can('countries-view')
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.countries.*') ? 'active' : '' }}" href="{{ route('admin.countries.index') }}">
                        <i class="fas fa-globe"></i>Countries
                    </a>
                </li>
                @endcan
                @can('social_media-view')
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.social-media.*') ? 'active' : '' }}" href="{{ route('admin.social-media.index') }}">
                        <i class="fab fa-facebook"></i>Social Media
                    </a>
                </li>
                @endcan
            </ul>
            @endcanany

        </div>
    </div>
</nav>
