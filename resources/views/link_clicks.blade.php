@extends(config('url-shortener.admin-template.name'))

@push(config('url-shortener.admin-template.styles_section'))
<style>
    .copy-btn:hover {
        cursor: pointer;
    }

    .link {
        text-decoration: none;
        color: inherit;
    }

    .link:hover {
        text-decoration: underline;
        cursor: pointer;
        color: inherit;
    }
</style>
@endpush

@section(config('url-shortener.admin-template.section'))
<div class="container">
    <div class="row mt-3">
        <div class="col-sm-12">
            <a href="{{ route(config('url-shortener.admin-route.as') . 'index') }}">&#60; Back</a>
            <h1>Link Clicks</h1>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-sm-12 mb-3">
            URL: {{ $link->short_url }} <br>
            Redirect To: <a href="{{ $link->long_url }}">{{ $link->long_url }}</a> <br>
            {{-- Created At: {{ $link->created_at->format(config('mail-tracker.date-format')) }} --}}
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <table class="table table-striped table-responsive">
                <tr>
                    <th>#</th>
                    <th>IP</th>
                    <th>User Agent</th>
                    <th>Referer</th>
                    <th>Referer Host</th>
                    <th>Clicked At</th>
                </tr>
                @foreach($clicks as $click)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $click->ip }}</td>
                    <td style="font-size: 12px;">{{ $click->user_agent }}</td>
                    <td>{{ $click->referer }}</td>
                    <td>{{ $click->referer_host }}</td>
                    <td>{{ $click->created_at->format(config('url-shortener.date-format')) }}</td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 text-center">
            {!! $clicks->render() !!}
        </div>
    </div>
</div>
@endsection