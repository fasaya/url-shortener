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
            <h1>Links</h1>
        </div>
    </div>
    <div class="row mt-3 mb-0">
        <div class="col-sm-6">
            <form action="{{ route(config('url-shortener.admin-route.as') . 'index') }}">
                <div class="input-group">
                    <input type="text" name="search" value="{{ request()->search ?? '' }}" class="form-control" placeholder="Search...">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit">Submit</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-sm-6 text-right">
            <a href="{{ route(config('url-shortener.admin-route.as') . 'create') }}" class="btn btn-primary">
                + New Link
            </a>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-sm-12">

            @if(session()->has('alert-success'))
            <div class="alert alert-success" role="alert">{{ session('alert-success') }}</div>
            @endif

            @if(session()->has('alert-error'))
            <div class="alert alert-danger" role="alert">{{ session('alert-danger') }}</div>
            @endif

            <table class="table table-striped">
                <tr>
                    <th>#</th>
                    <th>URL</th>
                    <th>Redirect To</th>
                    <th>Status</th>
                    <th>Clicks</th>
                    <th>Generated At</th>
                    <th>Click Report</th>
                </tr>
                @foreach($links as $link)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <a href="{{ route(config('url-shortener.admin-route.as') . 'edit', $link->id) }}" id="link_{{ $link->id }}" class="link">{{ $link->short_url }}</a>
                        {{-- &nbsp; --}}
                        <span onclick="copyText('{{ $link->short_url }}')" class="copy-btn">
                            <svg width='20' height='20' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'>
                                <rect width='20' height='20' stroke='none' fill='#000000' opacity='0' />
                                <g transform="matrix(1 0 0 1 12 12)">
                                    <path style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-dashoffset: 0; stroke-linejoin: miter; stroke-miterlimit: 4; fill: rgb(0,0,0); fill-rule: nonzero; opacity: 1;" transform=" translate(-12, -12)" d="M 4 2 C 2.895 2 2 2.895 2 4 L 2 18 L 4 18 L 4 4 L 18 4 L 18 2 L 4 2 z M 8 6 C 6.895 6 6 6.895 6 8 L 6 20 C 6 21.105 6.895 22 8 22 L 20 22 C 21.105 22 22 21.105 22 20 L 22 8 C 22 6.895 21.105 6 20 6 L 8 6 z M 8 8 L 20 8 L 20 20 L 8 20 L 8 8 z" stroke-linecap="round" />
                                </g>
                            </svg>
                        </span>
                    </td>
                    <td>{{ $link->long_url }}</td>
                    <td>
                        <a href="{{ route(config('url-shortener.admin-route.as') . 'edit', $link->id) }}">
                            @if ($link->is_disabled == 1)
                            <span class="badge badge-secondary">Disabled</span>
                            @else
                            <span class="badge badge-success">Active</span>
                            @endif
                        </a>
                    </td>
                    <td>{{ $link->clicks }}</td>
                    <td>{{ $link->created_at->format(config('url-shortener.date-format')) }}</td>
                    <td>
                        @if($link->clicks > 0)
                        <a href="{{ route(config('url-shortener.admin-route.as') . 'show', $link->id) }}">Link Report</a>
                        @else
                        No Clicks
                        @endif
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 text-center">
            {!! $links->render() !!}
        </div>
    </div>
</div>
@endsection