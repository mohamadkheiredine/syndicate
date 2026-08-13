@extends('web.layouts.main')

@push('stylesheets')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
@endpush

@section('content')

{{-- Start: Home Slider --}}
<div class="position-relative">
	<div class="simple-slider home-slider">
		@foreach($home_sliders as $index => $slider)
		<div class="custom-slider position-relative">
			<img class="cover slider-img"
				src="{{ $slider->display_image }}"
				data-desktop="{{ $slider->display_image }}"
				data-mobile="{{ $slider->display_mobile_image }}"
			>

			<div class="content">
				@if($slider->display_title)
				<h1>{{ $slider->display_title }}</h1>
				@endif

				@if($slider->display_subtitle)
				<h3>{{ $slider->display_subtitle }}</h3>
				@endif

				@if($slider->display_text)
				<p>{{ $slider->display_text }}</p>
				@endif

				@if($index === 0)
				<a href="https://play.google.com/store/apps/details?id=com.tedmob.syndicate&hl=en" target="_blank">
					<img src="{{ asset('assets-web/images/theme/google_play.svg') }}" style="width: 200px; height: auto; margin-right: 5px;">
				</a>
				<a href="https://apps.apple.com/us/app/lmo-syndicate/id1331939216" target="_blank">
					<img src="{{ asset('assets-web/images/theme/appstore.svg') }}" style="width: 200px; height: auto;">
				</a>
				@endif
			</div>
		</div>
		@endforeach
	</div>

	<div class="mouse-wrapper d-none d-lg-block">
		<div class="mouse">
			<div class="scroll"></div>
		</div>
	</div>
</div>
{{-- End: Home Slider --}}

{{-- Start: Calls to action --}}
<div id="home-cta">
	<div class="wow animated fadeInDown" data-wow-duration="0.5s">
		<div id="box-donate" class="position-relative box simple-half text-center bg-green">
			<h4 class="box-headline"><a href="{{ route('web.contact') }}">Have any suggestion!</a></h4>
			<a href="{{ route('web.contact') }}"><img class="arrow-right" src="{{ asset('assets-web/images/theme/arrow-right.svg') }}"></a>
		</div>
		<div id="box-volunteer" class="position-relative box simple-half text-center bg-red">
			<h4 class="box-headline"><a href="{{ route('web.form') }}">Join the syndicate</a></h4>
			<a href="{{ route('web.form') }}"><img class="arrow-right" src="{{ asset('assets-web/images/theme/arrow-right.svg') }}"></a>
		</div>
	</div>
</div>
{{-- End: Calls to action --}}

{{-- Start: PDF Downloads Section --}}
<div id="pdf-sections" style="padding: 2rem 0;">
	<div class="box full-width text-right bg-light" style="margin-bottom: 2rem; padding: 2rem;">
		<h4 class="box-headline" style="margin-bottom: 1.5rem; color: black">لائحة الزملاء المقترعين للإنتخابات النقابية  لعام ٢٠٢٥</h4>
		<ul style="list-style: none; padding: 0; margin: 0;">
			<li style="margin-bottom: 1rem;"><a href="{{ asset('assets-web/files/touch-1.pdf') }}" download>📄 الملف الاول</a></li>
			<li style="margin-bottom: 1rem;"><a href="{{ asset('assets-web/files/touch-2.pdf') }}" download>📄 الملف الثاني</a></li>
			<li style="margin-bottom: 1rem;"><a href="{{ asset('assets-web/files/parallel-1.pdf') }}" download>📄 الملف الثالث</a></li>
			<li style="margin-bottom: 1rem;"><a href="{{ asset('assets-web/files/parallel-2.pdf') }}" download>📄 الملف الرابع</a></li>
			<li style="margin-bottom: 1rem;"><a href="{{ asset('assets-web/files/elections-candidates-names.jpg') }}" download>📄 الملف الخامس</a></li>
		</ul>
	</div>

	<div class="box full-width text-right bg-light" style="padding: 2rem;">
		<h4 class="box-headline" style="margin-bottom: 1.5rem; color: black">أسماء الزملاء المرشحين للانتخابات النقابية لعام ٢٠٢٥</h4>
		<ul style="list-style: none; padding: 0; margin: 0;">
			<li style="margin-bottom: 1rem;"><a href="{{ asset('assets-web/files/names.xlsx') }}" download>📄 الملف الاول</a></li>
		</ul>
	</div>
</div>
{{-- End: PDF Downloads Section --}}

<main id="main">
	{{-- Start: Social Updates --}}
	<div class="section social-updates">
		<div class="q-container">
			<div id="social-slider" class="simple-slider wow animated fadeInUp" data-wow-duration="0.5s" data-wow-offset="200">
				<div class="simple-slide">
					<div class="social-update">منذ عرف الإنسان ألقيمة عرف العمل باعتباره العنصر الأساس والهام لإتمام أية عملية انتاجية، والعُمال وهم محور التنمية والتقدم لا يملكون إلا بيع قوة عملهم مقابل الأجر، وهم يسعون دائماً أن تكون الأجور كافية لمعيشتهم هم وأسرهم، بل ويأملون دائماً في زيادة هذه الأجور لتحسين معيشتهم. </div>
				</div>
				<div class="simple-slide">
					<div class="social-update">أقرت معظم تشريعات العالم بحق إنشاء النقابات العمالية لا بل بعضها اعتبر إلزامية قيام النقابات من الأمور الجوهرية في تحول الحركة العمالية . وأخذت هذه الهيئات موقعها في تركيب بنية الدولة من الوجهة الاقتصادية.</div>
				</div>
				<div class="simple-slide">
					<div class="social-update">ولم يكن موقف أصحاب العمل أقل قناعة بحتمية إنشاء النقابات في إطار من التعاون والتنسيق والثقة المتبادلة ، لا بل عمد بعض هؤلاء على تشجيع إنتاج النقابة في مواقع عملهم إيماناً منهم بالارتداد الإيجابي لذلك على سقف الإنتاج والعلاقة بين طرفيه .</div>
				</div>
				<div class="simple-slide">
					<div class="social-update">تأسست الحركة النقابية اللبنانية مطلع عشرينات القرن الماضي، وكانت ولادتها الأولى مرتبطة بتأسيس حزب العمال العام في لبنان الكبير عام 1921، مستنداً إلى عدد من النقابات: تعاونية الريجي في بكفيا، نقابة عمال المطابع، نقابة عمال سكة الحديد، نقابة الطهاة، جمعيتي النجارين والحلاقين، نقابة عمال زحلة، وغيرها.</div>
				</div>
				<div class="simple-slide">
					<div class="social-update">
						اتسمت هذه المرحلة بازدواج العمل النقابي المطلبي الساعي إلى حق العمال في تأسيس نقاباتهم المهنية وتنظيم أنفسهم، وفي النضال أيضاً من أجل تحقيق الاستقلال الوطني عن الانتداب الفرنسي. واجهت النقابات خلال هذه الفترة ضغوطاً من سلطات الانتداب ومحاولات لإجهاض تكوّنها ونضالاتها.
						يُنَظِّم قانون العمل اللبناني الصادر عام ١٩٤٦ طريقة وشروط تأسيس نقابة في لبنان.
					</div>
				</div>
				<div class="simple-slide">
					<div class="social-update">
						تعطي المادة ٨٣ من هذا القانون الحق لأرباب العمل وللأجراء في كل فئة من فئات المهن أن يؤلف كل منهم نقابة خاصة يكون لها الشخصية المعنوية وحق التقاضي.
						فالمادة ٨٤ من هذا القانون تحصر غاية النقابة في الأمور التي من شأنها حماية المهنة وتشجيعها ورفع مستواها والدفاع عن مصالحها والعمل على تقدمها من جميع الوجوه الاقتصادية والصناعية والتجارية.
						أما المادة ٨٥ فتَفرِضُ على النقابة المراد تأسيسها أن تجمع عمالا يمارسون مهنة واحدة أو مهنا متشابهة.
						أما المادة 90 فتعطي الحق لكل من رب العمل والأجير في الانتساب إلى النقابة اذا اراد ذلك.
					</div>
				</div>
			</div>
		</div>
	</div>
	{{-- End: Social Updates --}}

	{{-- Start: Article Grid --}}
	<div class="section article-grid">
		<div class="q-container">
			<div class="q-row">
				@if($is_logged_in && $latest_offer)
				<article class="q-col-1-3 wow animated fadeInUp" data-wow-offset="100" data-wow-duration="1s">
					<div class="article-headline">
						<h3 class="sub-headline deco-headline"><a href="{{ route('web.offers') }}">Offers Discount</a></h3>
					</div>
					<div class="article-summary">
						@if($latest_offer->display_image)
						<div class="article-image"><a href="{{ route('web.offers') }}"><img src="{{ $latest_offer->display_image }}" style="width:100%; height:175px; object-fit: cover;" alt=""></a></div>
						@endif
						<p>{{ ucfirst(stripslashes(html_entity_decode(substr($latest_offer->short_description, 0, 100)))) }}</p>
						<div class="text-right">
							<a href="{{ route('web.offers') }}"><button class="btn-sign-in">READ MORE</button></a>
						</div>
					</div>
				</article>
				@endif

				<article class="q-col-1-3 wow animated fadeInUp" data-wow-offset="200" data-wow-duration="1s">
					<div class="article-headline">
						<h3 class="sub-headline deco-headline"><a href="{{ route('web.aboutus') }}">Our Team</a></h3>
					</div>
					<div class="article-summary">
						@if($our_team)
						@if($our_team->display_image)
						<div class="article-image"><a href="{{ route('web.aboutus') }}"><img src="{{ $our_team->display_image }}" style="width:100%; height:175px; object-fit: cover;" alt=""></a></div>
						@endif
						<p>{{ stripslashes(html_entity_decode(substr($our_team->display_description, 0, 100))) }}</p>
						<div class="text-right">
							<a href="{{ route('web.aboutus') }}"><button class="btn-sign-in">READ MORE</button></a>
						</div>
						@endif
					</div>
				</article>

				@if($is_logged_in && $latest_news)
				<article class="q-col-1-3 wow animated fadeInUp" data-wow-offset="300" data-wow-duration="1s">
					<div class="article-headline">
						<h3 class="sub-headline deco-headline"><a href="{{ route('web.news') }}">Latest News</a></h3>
					</div>
					<div class="article-summary">
						@if($latest_news->display_image)
						<div class="article-image"><a href="{{ route('web.news') }}"><img src="{{ $latest_news->display_image }}" style="width:100%; height:175px; object-fit: cover;" alt=""></a></div>
						@endif
						<p>{{ ucfirst(stripslashes(html_entity_decode(substr($latest_news->short_description, 0, 100)))) }}</p>
						<div class="text-right">
							<a href="{{ route('web.news') }}"><button class="btn-sign-in">READ MORE</button></a>
						</div>
					</div>
				</article>
				@endif
			</div>
		</div>
	</div>
	{{-- End: Article Grid --}}

	{{-- Start: Achievement Section --}}
	<div class="section achievement-section text-center">
		<div class="q-container">
			<div class="q-row">
				<h1>Syndicate 2020 in Numbers</h1>
				<p>We need change. This is our time. Yes, we can seize our future.</p>

				<ul>
					@foreach($achievements as $achievement)
					<li>
						<div class="d-table w-100 h-100">
							<div class="d-table-cell align-middle">
								@if($achievement->display_number !== null && $achievement->display_number !== '')
								<h2>{{ $achievement->display_number }}+</h2>
								@endif

								@if($achievement->display_title)
								<p>{{ $achievement->display_title }}</p>
								@endif
							</div>
						</div>
					</li>
					@endforeach
				</ul>
			</div>
		</div>
	</div>
	{{-- End: Achievement Section --}}
</main>

@endsection

@push('scripts')
<script src="{{ asset('assets-web/libraries/modernizr/modernizr.min.js') }}"></script>
<script src="{{ asset('assets-web/libraries/jquery-1.11.1/jquery-1.11.1.min.js') }}"></script>
<script src="{{ asset('assets-web/libraries/jquery-1.11.1/jquery-migrate-1.2.1.min.js') }}"></script>
<script src="{{ asset('assets-web/libraries/jquery-ui/jquery-ui.min.js') }}"></script>
<script src="{{ asset('assets-web/libraries/hoverintent/jquery.hoverintent.min.js') }}"></script>
<script src="{{ asset('assets-web/libraries/superfish/jquery.superfish.min.js') }}"></script>
<script src="{{ asset('assets-web/libraries/owl-carousel/owl.carousel.min.js') }}"></script>
<script src="{{ asset('assets-web/libraries/easy-responsive-tabs/easyresponsivetabs.js') }}"></script>
<script src="{{ asset('assets-web/libraries/magnific-popup/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset('assets-web/libraries/mixitup/jquery.mixitup.min.js') }}"></script>
<script src="{{ asset('assets-web/libraries/fitvids/jquery.fitvids.js') }}"></script>
<script src="{{ asset('assets-web/libraries/mousewheel/jquery.mousewheel.min.js') }}"></script>
<script src="{{ asset('assets-web/libraries/smoothscroll/jquery.simplr.smoothscroll.min.js') }}"></script>
<script src="{{ asset('assets-web/libraries/wow/wow.min.js') }}"></script>
<script>
	function updateSliderImages() {
		const isMobile = window.innerWidth < 768;
		document.querySelectorAll('.slider-img').forEach(function(img) {
			img.src = isMobile ? img.dataset.mobile : img.dataset.desktop;
		});
	}
	window.addEventListener('load', updateSliderImages);
	window.addEventListener('resize', updateSliderImages);
</script>
@endpush
