<?php

namespace Fasaya\UrlShortener;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Fasaya\UrlShortener\Model\Link;
use Fasaya\UrlShortener\Requests\LinkStoreRequest;

class AdminController extends Controller
{
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
    public function store(Request $request)
    {
        $input = $request->all();
        // dd($input);
        $expiration_date =  $request->expiration_date ? date('Y-m-d H:i:s', strtotime($input['expiration_date'])) : NULL;

        $link = $request->is_custom_checkbox == 'on'
            ? UrlShortener::makeCustom($input['redirect_to'], $input['custom'], $expiration_date)
            : UrlShortener::make($input['redirect_to'], $expiration_date);

        return redirect()->route('url-shortener-manager.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $link = Link::findOrFail($id); // Find the Link by ID using Eloquent
        return view('links.show', compact('link'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $link = Link::findOrFail($id); // Find the Link by ID using Eloquent
        return view('links.edit', compact('link'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate and update the Link resource using Eloquent
        $validatedData = $request->validate([
            'url' => 'required|url|unique:links,url,' . $id,
        ]);

        $link = Link::findOrFail($id);
        $link->update($validatedData);

        return redirect()->route('links.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $link = Link::findOrFail($id); // Find the Link by ID using Eloquent
        $link->delete(); // Delete the Link using Eloquent

        return redirect()->route('links.index');
    }
}
