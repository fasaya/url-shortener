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
            <h1>Create Link</h1>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-sm-12">
            <form method="POST" action="{{ route(config('url-shortener.admin-route.as') . 'store') }}">
                @csrf
                <div class="form-group">
                    <label>URL</label>
                    <input type="text" class="form-control" name="redirect_to" value="{{ old('redirect_to') }}" placeholder="Enter the url you want to shorten" />
                    @error('redirect_to')<small class="form-text text-danger">{{ $message }}</small>@enderror
                </div>
                <div class="form-group">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="is_custom_checkbox" id="is_custom_checkbox" onclick="displayInput(this, 'custom')" />
                        <label class="form-check-label" for="is_custom_checkbox">Custom short URL</label>
                    </div>
                    <small class="form-text text-muted">If left unchecked, the shortened url will be randomized.</small>
                </div>
                <div class="form-group" style="display: none;" id="custom">
                    <label>Custom URL</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text font-italic">{{ config('app.url') }}/</div>
                        </div>
                        <input type="text" class="form-control" name="custom" value="{{ old('custom') }}" placeholder="my-pretty-custom-url" />
                    </div>
                    @error('custom')<small class="form-text text-danger">{{ $message }}</small>@enderror
                    <small class="form-text text-muted">Example: <i>{{ config('app.url') }}/my-pretty-custom-url</i></small>
                </div>
                <div class="form-group">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="have_expiration_date_checkbox" id="have_expiration_date_checkbox" onclick="displayInput(this, 'expiration_date')" />
                        <label class="form-check-label" for="have_expiration_date_checkbox">Have expiration date</label>
                    </div>
                    <small class="form-text text-muted">If left unchecked, the shortened url will not have expiration date. You can also edit/disable it later.</small>
                </div>
                <div class="form-group" style="display: none;" id="expiration_date">
                    <label>Expired At</label>
                    <input type="datetime-local" class="form-control" min="{{ $currentDate }}" value="{{ old('expiration_date') ?? $nextWeekDate }}" name="expiration_date" placeholder="Enter expiration date" />
                    @error('expiration_date')<small class="form-text text-danger">{{ $message }}</small>@enderror
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

        if (@json(old('is_custom_checkbox')) == 'on') {
            document.getElementById("is_custom_checkbox").click();
        }
        
        if (@json(old('have_expiration_date_checkbox')) == 'on') {
            document.getElementById("have_expiration_date_checkbox").click();
        }
    }, false);
</script>
@endpush