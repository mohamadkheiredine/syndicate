@extends('web.layouts.main')

@section('content')
<div class="page-header wow animated fadeInDown" data-wow-duration="0.5s">
    <div class="q-container">
        <div class="q-row">
            <div class="q-col-1-1">
                <h1 class="section-headline"><span>Promote</span> Advertise with us</h1>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="q-container">
        <div class="q-row">
            <div class="content q-col-1-1 wow animated fadeIn" data-wow-delay="0.2s" data-wow-duration="0.5s">
                <section class="section-donations">
                    <div class="q-row">
                        <div class="q-col-1-5">
                            <h2 class="section-headline"><span>Promote</span>Advertise</h2>
                        </div>

                        <div class="q-col-4-5">
                            <p>All-inclusive advertising package designed specifically to reach and convert local consumers. Get exclusive banner ad placement on our local website. Reach even more customers with our packages - all included. Different packages available to meet your budget and needs.</p>
                            <p>Advertise now!<strong> contact us to help you!</strong></p>
                        </div>
                    </div>

                    <div class="pricing-container q-row">
                        @forelse($ads as $ad)
                        <div class="q-col-1-4">
                            <ul class="pricing">
                                <li class="pricing-heading"><h3>{{ ucfirst($ad->advertise_type) }}</h3></li>
                                <li>{{ ucfirst($ad->title) }}</li>
                                <li>{{ $ad->dimension }}</li>
                                <li>{{ ucfirst($ad->what_type) }}</li>
                                <li class="pricing-value"><sup>$</sup>{{ $ad->price }}</li>
                                <li><a class="button">{{ ucfirst($ad->duration) }}</a></li>
                            </ul>
                        </div>
                        @empty
                        <font color="red">Data not found</font>
                        @endforelse
                    </div>

                    <h3>Contribution Rules</h3>

                    <div class="note">
                        <p>The Committee can accept <strong>all kind of advertisment</strong> to promote any business</p>

                        <p>But some major products and services <strong>are not acceptable</strong> you can check below:</p>

                        <ol>
                            <li>Alchool and smoking advertising</li>
                            <li>Politics and Visions advertising</li>
                            <li>Any advertising that does not reflect our vertues and image</li>
                        </ol>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection
