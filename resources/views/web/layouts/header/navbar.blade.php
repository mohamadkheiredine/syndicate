<header id="header">
	<div class="q-container">
		<div id="logo">
			@if($web_logo && $web_logo->logo)
			<a href="{{ route('web.home') }}"><img src="{{ $web_logo->logo }}" alt="logo" width="350"></a>
			@endif
		</div>

		<nav id="nav">
			<a href="#" id="nav-toggle" title="Navigation"><i class="fa fa-bars"></i></a>
			<ul>
				<li><a href="{{ route('web.home') }}">Home <span>Welcome</span></a></li>
				@if($is_logged_in)
				<li><a href="{{ route('web.activities') }}">Syndicate<span>Activities</span></a></li>
				@endif
				<li>
					<a href="{{ route('web.aboutus') }}">About <span>The Syndicate</span></a>
					<ul>
						<li><a>Members Previous Years</a>
							<ul>
								@foreach($web_family_years as $year)
								<li><a href="{{ route('web.previous-members', $year) }}">{{ $year }}</a></li>
								@endforeach
							</ul>
						</li>
					</ul>
				</li>
				@if($is_logged_in)
				<li><a href="{{ route('web.offers') }}">Offers <span>Specials</span></a></li>
				<li><a href="{{ route('web.news') }}">Media <span>Up to date</span></a></li>
				@endif
				<li class="nav-special nav-reverse">
					<a href="{{ route('web.page-donate') }}">Get Involved <span>Advertise &amp; Share</span></a>
					<ul>
						<li><a href="{{ route('web.page-donate') }}">Advertise</a></li>
						<li><a href="{{ route('web.form') }}">Fill your info</a></li>
						<li><a href="{{ route('web.contact') }}">Contact</a></li>
					</ul>
				</li>
				<li>
					@if(!$is_logged_in)
					<a href="{{ route('web.user-login') }}"><button class="btn-sign-in">SIGN IN</button></a>
					@else
					<a href="{{ route('web.logout') }}"><button class="btn-sign-in">LOG OUT</button></a>
					@endif
				</li>
			</ul>
		</nav>
	</div>
</header>
