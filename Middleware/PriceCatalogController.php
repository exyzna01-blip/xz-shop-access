<?php

namespace App\Http\Controllers;

use App\Models\PriceCatalogItem;
use Illuminate\Http\Request;

class PriceCatalogController extends Controller
{
    public function index()
    {
        $items = PriceCatalogItem::query()->orderBy('service')->orderBy('category')->get();
        return view('owner.catalog', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'service' => ['required','string'],
            'duration' => ['nullable','string'],
            'category' => ['required','string'],
            'devices' => ['nullable','string'],
            'price' => ['required','numeric','min:0'],
            'active' => ['nullable','boolean'],
        ]);

        PriceCatalogItem::create([
            'service' => $request->service,
            'duration' => $request->duration ?: null,
            'category' => $request->category,
            'devices' => $request->devices ?? '',
            'price' => $request->price,
            'active' => $request->boolean('active', true),
        ]);

        return back()->with('ok','Catalog item added.');
    }

    public function update(Request $request, PriceCatalogItem $item)
    {
        $request->validate([
            'service' => ['required','string'],
            'duration' => ['nullable','string'],
            'category' => ['required','string'],
            'devices' => ['nullable','string'],
            'price' => ['required','numeric','min:0'],
            'active' => ['nullable','boolean'],
        ]);

        $item->update([
            'service' => $request->service,
            'duration' => $request->duration ?: null,
            'category' => $request->category,
            'devices' => $request->devices ?? '',
            'price' => $request->price,
            'active' => $request->boolean('active', true),
        ]);

        return back()->with('ok','Catalog item updated.');
    }

    public function destroy(PriceCatalogItem $item)
    {
        $item->delete();
        return back()->with('ok','Catalog item deleted.');
    }
}
