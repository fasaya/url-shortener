@extends(config('url-shortener.admin-template.name'))

@push(config('url-shortener.admin-template.styles_section'))
<style>
    .form-group {
        margin-bottom: 18px !important;
    }
</style>
@endpush

@section(config('url-shortener.admin-template.section'))
<div class="container">
    <div class="row mt-3">
        <div class="col-sm-12">
            <a href="{{ route(config('url-shortener.admin-route.as') . 'index') }}">&#60; Back</a>
            <h1>Update Link</h1>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-sm-12">
            <form method="POST" action="{{ route(config('url-shortener.admin-route.as') . 'update', $link->id) }}">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label>URL</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Redirect to URL" value="{{ $link->long_url }}" readonly>
                        <div class="input-group-append">
                            <a class="btn btn-outline-secondary" href="{{ $link->long_url }}" target="_blank">Open</a>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Short URL</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Short URL" value="{{ $link->short_url }}" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" onclick="copyText('{{ $link->short_url }}')">Copy</button>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="have_expiration_date_checkbox" id="have_expiration_date_checkbox" onclick="displayInput(this, 'expiration_date')" />
                        <label class="form-check-label" for="have_expiration_date_checkbox">Have expiration date</label>
                    </div>
                </div>
                <div class="form-group" style="display: none;" id="expiration_date">
                    <label>Expired At</label>
                    <input type="datetime-local" class="form-control" min="{{ $created_at }}" value="{{ $link->expired_at ?? '' }}" name="expiration_date" placeholder="Enter expiration date" />
                    @error('expiration_date')<small class="form-text text-danger">{{ $message }}</small>@enderror
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="is_disabled" id="inlineRadio1" value="0" {{ $link->is_disabled == 0 ? 'checked' : '' }}>
                            <label class="form-check-label" for="inlineRadio1">Active</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="is_disabled" id="inlineRadio2" value="1" {{ $link->is_disabled == 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="inlineRadio2">Disabled</label>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-2">Submit</button>
            </form>
        </div>
    </div>
</div>
@endsection


@push(config('url-shortener.admin-template.script_section'))
<script>
    function displayInput(el, form_input_id) {
        const checkbox = el;
        const formInput = document.getElementById(form_input_id);
        formInput.style.display = checkbox.checked ? 'block' : 'none';
    }

    document.addEventListener('DOMContentLoaded', function () {
        if (@json($link->expired_at != null)) {
            document.getElementById("have_expiration_date_checkbox").click();
        }
    }, false);
</script>
@endpush