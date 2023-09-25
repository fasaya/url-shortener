<?php

namespace Fasaya\UrlShortener;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class UrlShortenerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $slug)
    {
        return UrlShortener::redirect($request, $slug);
    }
}
