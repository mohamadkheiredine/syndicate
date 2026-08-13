<footer id="footer">
	<div class="q-container">
		<div class="q-row">
			<div class="q-col-1-4 text-left">
				<ul class="social-profiles">
					@if($web_settings && $web_settings->facebook)
					<li><a href="{{ $web_settings->facebook }}" target="_blank" class="social-button social-facebook" title="Facebook"><i class="fa fa-facebook"></i></a></li>
					@endif
					<li><a href="{{ route('web.contact') }}"><u>Contact</u></a></li>
					<li><a href="{{ route('web.terms') }}"><u>Terms &amp; Conditions</u></a></li>
				</ul>
			</div>
			<div class="q-col-1-2 text-center">
				Copyright Mobile Operators Syndicate Lebanon© | All rights reserved
			</div>
			<div class="q-col-1-4 text-right">
				Powered by <a href="https://tedmob.com/" target="_blank">TEDMOB.COM</a>
			</div>
		</div>
	</div>
</footer>
