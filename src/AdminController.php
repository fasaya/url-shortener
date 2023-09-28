<?php

namespace Fasaya\UrlShortener;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Fasaya\UrlShortener\Model\Link;
use Fasaya\UrlShortener\Model\LinkClick;
use Fasaya\UrlShortener\Requests\LinkStoreRequest;
use Fasaya\UrlShortener\Requests\LinkUpdateRequest;

class AdminController extends Controller
{
    public $route;

    public function __construct()
    {
        $this->route = config('url-shortener.admin-route.as');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $links = Link::query();

        if ($search = $request->search) {
            $links = $links->where(function ($query) use ($search) {
                $query->orWhere('slug', 'LIKE', '%' . $search . '%');
                $query->where('short_url', 'LIKE', '%' . $search . '%');
                $query->orWhere('long_url', 'LIKE', '%' . $search . '%');
            });
        }

        $links = $links
            ->orderBy('id', 'DESC')
            ->paginate(config('url-shortener.links-per-page'));

        return view('url-shortener::index', compact('links'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $date = new \DateTime();
        $currentDate = $date->format('Y-m-d\TH:i');
        $nextWeekDate = $date->add(new \DateInterval('P7D'))->format('Y-m-d\TH:i');

        return view('url-shortener::create', compact('currentDate', 'nextWeekDate'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LinkStoreRequest $request)
    {
        $input = $request->all();

        $expiration_date =  $request->expiration_date ? date('Y-m-d H:i:s', strtotime($input['expiration_date'])) : NULL;

        $link = $request->is_custom_checkbox == 'on'
            ? UrlShortener::makeCustom($input['redirect_to'], $input['custom'], $expiration_date)
            : UrlShortener::make($input['redirect_to'], $expiration_date);

        session()->flash('alert-success', 'Data created successfully');
        return redirect()->route($this->route . 'index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Link $link)
    {
        $clicks = LinkClick::where('short_link_id', $link->id)->orderBy('id', 'DESC')->paginate(config('url-shortener.links-per-page'));
        return view('url-shortener::link_clicks', compact('link', 'clicks'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Link $link)
    {
        $date = new \DateTime($link->created_at);
        $created_at = $date->format('Y-m-d\TH:i');

        return view('url-shortener::edit', compact('link', 'created_at'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LinkUpdateRequest $request, Link $link)
    {
        if (UrlShortener::getQuery($link->slug)->where('id', '!=', $link->id)->exists() && $request->is_disabled == 0) {
            session()->flash('alert-error', 'Failed to update. Other identical URL is currently active, disable other to activate.');
            return redirect()->route($this->route . 'index');
        }

        $link->update([
            'expired_at' => $request->have_expiration_date_checkbox === 'on' ? $request->expiration_date : null,
            'is_disabled' => $request->is_disabled
        ]);

        session()->flash('alert-success', 'Data updated successfully');
        return redirect()->route($this->route . 'index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Link $link)
    {
        $link->delete();

        session()->flash('alert-success', 'Data updated successfully');
        return redirect()->route($this->route . 'index');
    }
}
