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
            @canany(['dashboard-view', 'logo-edit', 'admins-view', 'roles-view'])
            <ul class="navbar-nav">
                @can('dashboard-view')
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-drafting-compass text-primary"></i>Dashboard
                    </a>
                </li>
                @endcan
                @can('logo-edit')
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.logo.*') ? 'active' : '' }}" href="{{ route('admin.logo.edit') }}">
                        <i class="fas fa-image text-primary"></i>Logo
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
            </ul>

            <hr class="my-3">
            @endcanany

            <h6 class="navbar-heading text-muted">MANAGEMENT</h6>
            <ul class="navbar-nav mb-3">
                @can('banners-view')
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.banners.*') ? 'active' : '' }}" href="{{ route('admin.banners.index') }}">
                        <i class="fas fa-images"></i>Banner Manager
                    </a>
                </li>
                @endcan
                @can('news-view')
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.news.*') ? 'active' : '' }}" href="{{ route('admin.news.index') }}">
                        <i class="fas fa-newspaper"></i>Media up to date Manager
                    </a>
                </li>
                @endcan
                @can('syndicate_activities-view')
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.syndicate-activities.*') ? 'active' : '' }}" href="{{ route('admin.syndicate-activities.index') }}">
                        <i class="fas fa-calendar-check"></i>Syndicate Activities Manager
                    </a>
                </li>
                @endcan
                @can('syndicate_offers-view')
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.syndicate-offers.*') ? 'active' : '' }}" href="{{ route('admin.syndicate-offers.index') }}">
                        <i class="fas fa-tags"></i>Offers Specials Manager
                    </a>
                </li>
                @endcan
                @can('syndicate_family-view')
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.syndicate-family.*') ? 'active' : '' }}" href="{{ route('admin.syndicate-family.index') }}">
                        <i class="fas fa-users"></i>Syndicate Family Manager
                    </a>
                </li>
                @endcan
                @can('our_team-edit')
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.our-team.*') ? 'active' : '' }}" href="{{ route('admin.our-team.edit') }}">
                        <i class="fas fa-user-friends"></i>Our Team Manager
                    </a>
                </li>
                @endcan
                @can('about_syndicate-edit')
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.about-syndicate.*') ? 'active' : '' }}" href="{{ route('admin.about-syndicate.edit') }}">
                        <i class="fas fa-info-circle"></i>About The Syndicate Manager
                    </a>
                </li>
                @endcan
                @can('terms_conditions-edit')
                <li class="nav-item">
                    <a class="nav-link" href="#terms-conditions-menu" data-toggle="collapse" role="button" aria-expanded="{{ Route::is('admin.terms-conditions.*') ? 'true' : 'false' }}" aria-controls="terms-conditions-menu">
                        <i class="fas fa-file-contract text-primary"></i>
                        <span class="nav-link-text">Terms & Conditions</span>
                    </a>
                    <div class="collapse {{ Route::is('admin.terms-conditions.*') ? 'show' : '' }}" id="terms-conditions-menu">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->route('page') === 'terms-conditions' ? 'active' : '' }}" href="{{ route('admin.terms-conditions.edit', 'terms-conditions') }}">
                                    Terms & Conditions
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->route('page') === 'rules' ? 'active' : '' }}" href="{{ route('admin.terms-conditions.edit', 'rules') }}">
                                    Rules
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->route('page') === 'education' ? 'active' : '' }}" href="{{ route('admin.terms-conditions.edit', 'education') }}">
                                    Education
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endcan
                @can('syndicate_advertisement-view')
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.syndicate-advertisement.*') ? 'active' : '' }}" href="{{ route('admin.syndicate-advertisement.index') }}">
                        <i class="fas fa-ad"></i>Get Involved Advertise Manager
                    </a>
                </li>
                @endcan
                @can('syndicate_others_advertisement-view')
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.syndicate-others-advertisement.*') ? 'active' : '' }}" href="{{ route('admin.syndicate-others-advertisement.index') }}">
                        <i class="fas fa-bullhorn"></i>Others Advertisement Manager
                    </a>
                </li>
                @endcan
                @can('home_sliders-view')
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.home-sliders.*') ? 'active' : '' }}" href="{{ route('admin.home-sliders.index') }}">
                        <i class="fas fa-sliders-h"></i>Home Sliders
                    </a>
                </li>
                @endcan
                @can('achievements-view')
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.achievements.*') ? 'active' : '' }}" href="{{ route('admin.achievements.index') }}">
                        <i class="fas fa-trophy"></i>Achievements
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
                @can('reports-view')
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.reports.*') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}">
                        <i class="fas fa-chart-line"></i>Reporting
                    </a>
                </li>
                @endcan
                @can('documents-view')
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.documents.*') ? 'active' : '' }}" href="{{ route('admin.documents.index') }}">
                        <i class="fas fa-file"></i>Documents
                    </a>
                </li>
                @endcan
                @can('income-view')
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.income.*') ? 'active' : '' }}" href="{{ route('admin.income.index') }}">
                        <i class="fas fa-sort-amount-up"></i>Income
                    </a>
                </li>
                @endcan
                @can('expenses-view')
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.expenses.*') ? 'active' : '' }}" href="{{ route('admin.expenses.index') }}">
                        <i class="fas fa-sort-amount-down"></i>Expenses
                    </a>
                </li>
                @endcan
                @can('yearly_payment-view')
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.yearly-payment.*') ? 'active' : '' }}" href="{{ route('admin.yearly-payment.index') }}">
                        <i class="fas fa-calendar-alt"></i>Yearly Payment
                    </a>
                </li>
                @endcan
                @can('election_fees-view')
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.election-fees.*') ? 'active' : '' }}" href="{{ route('admin.election-fees.index') }}">
                        <i class="fas fa-dollar-sign"></i>Election Fees
                    </a>
                </li>
                @endcan
                @can('push_notifications-create')
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('admin.push-notifications.*') ? 'active' : '' }}" href="{{ route('admin.push-notifications.index') }}">
                        <i class="fas fa-bell"></i>Push Notifications
                    </a>
                </li>
                @endcan
            </ul>
        </div>
    </div>
</nav>
