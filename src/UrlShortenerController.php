<?php

namespace Fasaya\UrlShortener;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
