<?php

namespace Fasaya\UrlShortener;

use Fasaya\UrlShortener\Model\Link;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

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

        $links = $links->paginate(config('url-shortener.links-per-page'));

        return view('url-shortener::index', compact('links'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('links.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $link = Link::create($validatedData);

        return redirect()->route('links.index');
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
