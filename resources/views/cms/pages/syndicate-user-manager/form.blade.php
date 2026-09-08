{{-- Shared fields for create + edit. Mirrors mobilesyndicate-master's
     Syndicate User Manager form exactly. $row is set on edit only. --}}

{{-- Photo. Required on create; on edit, leave empty to keep the current one
     (asterix stays true so no "remove" button renders - matches old, which
     had no remove-photo action). --}}
@include('cms.components.inputs.image', ['label' => 'Upload photo passport', 'name' => 'photo', 'asterix' => true])

{{-- First Name --}}
@include('cms.components.inputs.text', ['label' => 'First Name', 'asterix' => true, 'name' => 'first_name', 'maxlength' => 255])

{{-- Father's Name --}}
@include('cms.components.inputs.text', ['label' => "Father's Name", 'asterix' => true, 'name' => 'fathers_name', 'maxlength' => 255])

{{-- Last Name --}}
@include('cms.components.inputs.text', ['label' => 'Last Name', 'asterix' => true, 'name' => 'last_name', 'maxlength' => 255])

{{-- Mother's Full Name --}}
@include('cms.components.inputs.text', ['label' => "Mother's Full Name", 'asterix' => true, 'name' => 'mothers_name', 'maxlength' => 255])

{{-- DOB --}}
@include('cms.components.inputs.date', ['label' => 'DOB', 'asterix' => true, 'name' => 'dob'])

{{-- Mobile Number --}}
@include('cms.components.inputs.text', ['label' => 'Mobile Number', 'asterix' => true, 'name' => 'mobile_number', 'maxlength' => 255])

{{-- Home Number --}}
@include('cms.components.inputs.text', ['label' => 'Home Number', 'asterix' => true, 'name' => 'home_number', 'maxlength' => 255])

{{-- Email --}}
@include('cms.components.inputs.text', ['label' => 'Email Address', 'type' => 'email', 'asterix' => true, 'name' => 'email', 'maxlength' => 255])

{{-- Password --}}
@include('cms.components.inputs.text', ['label' => 'Password', 'type' => 'password', 'asterix' => !isset($row), 'name' => 'password', 'text' => isset($row) ? 'Leave blank to keep the current password. Minimum 6 characters.' : 'Minimum 6 characters.'])

{{-- Company --}}
@include('cms.components.inputs.select-single', ['label' => 'Company', 'asterix' => true, 'name' => 'company', 'placeholder' => 'Select Company', 'rows' => [['id' => 'Alfa', 'title' => 'Alfa'], ['id' => 'Touch', 'title' => 'Touch']], 'value_attribute' => 'id', 'attribute' => 'title'])

{{-- Blood Type --}}
@include('cms.components.inputs.select-single', ['label' => 'Blood Type', 'asterix' => true, 'name' => 'blood_type', 'placeholder' => 'Select Blood Type', 'rows' => [['id' => 'A+', 'title' => 'A+'], ['id' => 'A-', 'title' => 'A-'], ['id' => 'B+', 'title' => 'B+'], ['id' => 'B-', 'title' => 'B-'], ['id' => 'AB+', 'title' => 'AB+'], ['id' => 'AB-', 'title' => 'AB-'], ['id' => 'O+', 'title' => 'O+'], ['id' => 'O-', 'title' => 'O-']], 'value_attribute' => 'id', 'attribute' => 'title'])

{{-- Profile Link --}}
@include('cms.components.inputs.select-single', ['label' => 'Profile Link', 'asterix' => true, 'name' => 'profile_link', 'placeholder' => 'Select Profile Link', 'rows' => [['id' => 'FB', 'title' => 'Facebook'], ['id' => 'LINKEDIN', 'title' => 'Linkedin'], ['id' => 'BOTH', 'title' => 'Both']], 'value_attribute' => 'id', 'attribute' => 'title'])

{{-- Facebook URL (used when Profile Link is Facebook or Both) --}}
@include('cms.components.inputs.text', ['label' => 'Facebook URL', 'name' => 'facebook', 'maxlength' => 255])

{{-- Linkedin URL (used when Profile Link is Linkedin or Both) --}}
@include('cms.components.inputs.text', ['label' => 'Linkedin URL', 'name' => 'linkedin', 'maxlength' => 255])

{{-- Any File (pdf / doc / docx) --}}
<div class="form-group">
    <label class="form-control-label d-block">Upload Any File Pdf/Word</label>
    @if(isset($row) && $row->getAttributes()['any_file'])
    <p class="mb-2"><a href="{{ $row->any_file }}" target="_blank">{{ $row->getAttributes()['any_file'] }}</a></p>
    @endif
    <input name="any_file" type="file" class="form-control form-control-alternative">
    <small><em>Optional. Allowed: pdf, doc, docx.</em></small>
    @include('cms.components.inputs.error', ['name' => 'any_file'])
</div>
