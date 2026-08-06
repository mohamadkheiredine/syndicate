@extends('cms.layouts.main')

@section('content')
@include('cms.layouts.headers.partials', ['title' => 'Add Payment'])

<div class="container-fluid mt--7">
    <div class="card bg-secondary shadow">
        <div class="card-header bg-white border-0">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="mb-0">{{ $page_info['title'] }}</h3>
                </div>
                <div class="col-auto text-right">
                    <a href="{{ route('admin.'.$page_info['link'].'.index') }}" class="btn btn-sm btn-primary">Back to list</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            @include('cms.components.alert', ['with' => 'error', 'bg' => 'danger'])

            <form method="post" action="{{ route('admin.'.$page_info['link'].'.store') }}" autocomplete="off">
                @csrf

                <div class="form-group">
                    <label class="form-control-label">Syndicate User <span class="text-danger">*</span></label>
                    <select id="user_id" name="user_id" class="select2-custom form-control" required>
                        <option value="" selected disabled>Choose User</option>
                        @foreach($users as $user)
                        <option value="{{ $user->id }}" @if(old('user_id') == $user->id) selected @endif>
                            {{ $user->first_name }} {{ $user->fathers_name }} {{ $user->last_name }} - {{ $user->email }}
                        </option>
                        @endforeach
                    </select>
                    @include('cms.components.inputs.error', ['name' => 'user_id'])
                </div>

                {{-- Receipt Number --}}
                @include('cms.components.inputs.text', ['label' => 'Receipt Number', 'asterix' => true, 'name' => 'receipt', 'maxlength' => 255])

                <div class="form-group">
                    <label class="form-control-label">Payment Due <span class="text-danger">*</span></label>
                    <select id="years" name="years[]" class="select2-custom form-control" multiple required disabled>
                        <option value="">Choose a user first</option>
                    </select>
                    <div id="years-message" class="text-danger small mt-1"></div>
                    @include('cms.components.inputs.error', ['name' => 'years'])
                </div>

                <div class="text-center">
                    <button type="submit" id="save-btn" class="btn btn-success mt-4">Save</button>
                </div>
            </form>
        </div>
    </div>

    @include('cms.layouts.footers.auth')
</div>
@endsection

@push('script')
<script>
    $(function () {
        var $years = $('#years');
        var $message = $('#years-message');
        var $submit = $('#save-btn');

        // select2 draws its own widget on top of the <select>; just appending options
        // and triggering 'change' doesn't reliably refresh it (especially toggling
        // disabled -> enabled), so destroy and rebuild it on every user change instead.
        function rebuildYears(options, disabled) {
            if ($years.hasClass('select2-hidden-accessible')) {
                $years.select2('destroy');
            }
            $years.empty().append(options).prop('disabled', disabled);
            $years.select2();
        }

        function loadOutstandingYears(userId, preselectYears) {
            $message.text('');
            $submit.prop('disabled', false);
            rebuildYears($('<option>').text('Loading...').attr('value', ''), true);

            if (!userId) {
                return;
            }

            $.ajax({
                type: 'get',
                dataType: 'json',
                url: '{{ url("admin/members-payment/outstanding-years") }}/' + userId,
                success: function (response) {
                    if ($.isEmptyObject(response)) {
                        rebuildYears($('<option>').text('No outstanding years').attr('value', ''), true);
                        $message.text('This user has no outstanding payments due — there is nothing to add.');
                        $submit.prop('disabled', true);
                        return;
                    }

                    var options = [];
                    $.each(response, function (year, amount) {
                        var $option = $('<option>').text(year + ' — ' + amount + '$').attr('value', year);
                        if (preselectYears && preselectYears.indexOf(String(year)) !== -1) {
                            $option.prop('selected', true);
                        }
                        options.push($option);
                    });
                    rebuildYears(options, false);
                }
            });
        }

        $('#user_id').on('change', function () {
            loadOutstandingYears(this.value, null);
        });

        // After a failed submission (e.g. duplicate receipt), the user stays selected via
        // old('user_id') but the browser never fires 'change' just because an option was
        // pre-selected in HTML — so re-run the lookup ourselves, and restore whichever
        // years were previously picked.
        var initialUserId = $('#user_id').val();
        if (initialUserId) {
            var previouslySelectedYears = @json(array_map('strval', old('years', [])));
            loadOutstandingYears(initialUserId, previouslySelectedYears);
        }
    });
</script>
@endpush
