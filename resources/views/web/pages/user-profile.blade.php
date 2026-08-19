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

                <form action="{{ route('web.user-profile.update') }}" name="regis" id="regis" method="post" class="note" enctype="multipart/form-data">
                    @csrf

                    <h3>Your Profile:</h3>
                    @if($errors->any())
                    <div class="groupRed" align="center" style="color:#000;">{{ $errors->first() }}</div>
                    @endif
                    @if(session('profile_status'))
                    <div class="groupGreen" align="center" style="color:#000;">{{ session('profile_status') }}</div>
                    @endif

                    <p>
                        @if($user->photo)
                        <img src="{{ $user->photo }}" alt="" width="170">
                        @endif
                        <br>
                        @if($user->getAttributes()['any_file'])
                        @php $ext = strtolower(pathinfo($user->getAttributes()['any_file'], PATHINFO_EXTENSION)); @endphp
                        <a href="{{ $user->any_file }}" target="_blank" title="{{ in_array($ext, ['doc', 'docx']) ? 'Word Document' : 'Pdf Document' }}">
                            <i class="fa {{ in_array($ext, ['doc', 'docx']) ? 'fa-file-word-o' : 'fa-file-pdf-o' }} fa-2x"></i>
                        </a>
                        @endif
                        @if($user->linkedin)
                        <a href="{{ $user->linkedin }}" target="_blank" style="float:right;"><i class="fa fa-linkedin fa-2x"></i></a>
                        @endif
                        @if($user->facebook)
                        <a href="{{ $user->facebook }}" target="_blank" style="float:right; margin-right:10px;"><i class="fa fa-facebook fa-2x"></i></a>
                        @endif
                    </p>

                    <div class="q-row">
                        <div class="q-col-1-2">
                            <p><input type="text" placeholder="{{ __('first-name') }}" name="first_name" value="{{ old('first_name', $user->first_name) }}" id="first_name" class="full-width" style="width:98%;"></p>
                        </div>
                        <div class="q-col-1-2">
                            <p><input type="text" placeholder="{{ __('fathers-name') }}" name="fathers_name" value="{{ old('fathers_name', $user->fathers_name) }}" id="fathers_name" class="full-width" style="width:98%;"></p>
                        </div>
                    </div>
                    <div class="q-row">
                        <div class="q-col-1-2">
                            <p><input type="text" placeholder="{{ __('last-name') }}" name="last_name" value="{{ old('last_name', $user->last_name) }}" id="last_name" class="full-width" style="width:98%;"></p>
                        </div>
                        <div class="q-col-1-2">
                            <p><input type="text" placeholder="{{ __('mothers-name') }}" name="mothers_name" value="{{ old('mothers_name', $user->mothers_name) }}" id="mothers_name" class="full-width" style="width:98%;"></p>
                        </div>
                    </div>
                    <div class="q-row">
                        <div class="q-col-1-2">
                            <p><input type="text" placeholder="{{ __('dob') }}" name="dob" readonly value="{{ $user->dob ? $user->dob->format('Y-m-d') : '' }}" id="dob" class="full-width" style="width:98%;"></p>
                        </div>
                        <div class="q-col-1-2">
                            <p><input type="tel" placeholder="{{ __('mobile-number') }}" name="mobile_number" value="{{ old('mobile_number', $user->mobile_number) }}" id="mobile_number" class="full-width" style="width:98%;"></p>
                        </div>
                        <div class="q-col-1-2">
                            <p><input type="text" placeholder="{{ __('home-number') }}" name="home_number" value="{{ old('home_number', $user->home_number) }}" id="home_number" class="full-width" style="width:98%;"></p>
                        </div>
                        <div class="q-col-1-2">
                            <p><input type="text" placeholder="{{ __('email-address') }}" name="email" value="{{ old('email', $user->email) }}" id="email" class="full-width" style="width:98%;"></p>
                        </div>
                    </div>
                    <div class="q-row">
                        <div class="q-col-1-2">
                            <p><input type="password" placeholder="{{ __('password') }}" name="password" id="password" class="full-width" style="width:98%;"></p>
                        </div>
                        <div class="q-col-1-2">
                            <p><select name="company" id="company" style="width:98%; height:37px; border:2px solid #06F; border-radius:5px;">
                                <option value="">-{{ __('select-company') }}-</option>
                                <option value="Alfa" {{ old('company', $user->company) == 'Alfa' ? 'selected' : '' }}>{{ __('alfa') }}</option>
                                <option value="Touch" {{ old('company', $user->company) == 'Touch' ? 'selected' : '' }}>{{ __('touch') }}</option>
                            </select></p>
                        </div>
                    </div>
                    <div class="q-row">
                        <div class="q-col-1-2">
                            <p><select name="blood_type" id="blood_type" style="width:98%; height:37px; border:2px solid #06F; border-radius:5px;">
                                <option value="">-{{ __('select-blood-type') }}-</option>
                                @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bt)
                                <option value="{{ $bt }}" {{ old('blood_type', $user->blood_type) == $bt ? 'selected' : '' }}>{{ $bt }}</option>
                                @endforeach
                            </select></p>
                        </div>
                        <div class="q-col-1-2">
                            <p><select name="profile_link" id="profile_link" style="width:98%; height:37px; border:2px solid #06F; border-radius:5px;" onchange="socialToggle(this.value);">
                                <option value="">-{{ __('social-profile-link') }}-</option>
                                <option value="FB" {{ old('profile_link', $user->profile_link) == 'FB' ? 'selected' : '' }}>{{ __('fb-option') }}</option>
                                <option value="LINKEDIN" {{ old('profile_link', $user->profile_link) == 'LINKEDIN' ? 'selected' : '' }}>{{ __('linkedin-option') }}</option>
                                <option value="BOTH" {{ old('profile_link', $user->profile_link) == 'BOTH' ? 'selected' : '' }}>{{ __('both-option') }}</option>
                            </select></p>
                        </div>
                    </div>
                    <div class="q-row">
                        <div class="q-col-1-2"></div>
                        <div class="q-col-1-2" id="fb-fields" style="display:{{ old('profile_link', $user->profile_link) == 'FB' ? 'block' : 'none' }};">
                            <p>{{ __('facebook-url') }}</p>
                            <p style="margin-top:-15px;"><input type="text" placeholder="{{ __('facebook') }}" name="facebook_fb" value="{{ old('facebook_fb', $user->facebook) }}" id="facebook_fb" class="full-width" style="width:98%;"></p>
                        </div>
                        <div class="q-col-1-2" id="linkedin-fields" style="display:{{ old('profile_link', $user->profile_link) == 'LINKEDIN' ? 'block' : 'none' }};">
                            <p>{{ __('linkedin-url') }}</p>
                            <p style="margin-top:-15px;"><input type="text" placeholder="{{ __('linkedin') }}" name="linkedin_li" value="{{ old('linkedin_li', $user->linkedin) }}" id="linkedin_li" class="full-width" style="width:98%;"></p>
                        </div>
                        <div class="q-col-1-2" id="both-fields" style="display:{{ old('profile_link', $user->profile_link) == 'BOTH' ? 'block' : 'none' }};">
                            <p>{{ __('facebook-url') }}</p>
                            <p style="margin-top:-15px;"><input type="text" placeholder="{{ __('facebook') }}" name="facebook_both" value="{{ old('facebook_both', $user->facebook) }}" id="facebook_both" class="full-width" style="width:98%;"></p>
                            <p>{{ __('linkedin-url') }}</p>
                            <p style="margin-top:-15px;"><input type="text" placeholder="{{ __('linkedin') }}" name="linkedin_both" value="{{ old('linkedin_both', $user->linkedin) }}" id="linkedin_both" class="full-width" style="width:98%;"></p>
                        </div>
                    </div>
                    <div class="q-row">
                        <div class="q-col-1-2">
                            <p>{{ __('upload-photo') }}<br><input type="file" name="photo" id="photo" class="full-width"></p>
                        </div>
                        <div class="q-col-1-2">
                            <p>{{ __('upload-file') }}<br><input type="file" name="any_file" id="any_file" class="full-width"></p>
                        </div>
                    </div>

                    <p><input type="submit" name="update" value="{{ __('update-profile') }}"></p>
                </form>
            </div>

            @include('web.layouts.sidebar.right-panel')
        </div>
    </div>
</div>

@push('scripts')
<script type="text/javascript">
    function socialToggle(val) {
        document.getElementById('fb-fields').style.display = (val === 'FB') ? 'block' : 'none';
        document.getElementById('linkedin-fields').style.display = (val === 'LINKEDIN') ? 'block' : 'none';
        document.getElementById('both-fields').style.display = (val === 'BOTH') ? 'block' : 'none';
    }
</script>
@endpush
@endsection
