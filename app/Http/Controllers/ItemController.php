<?php


namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::where('admin_id',  Auth::id())->get();
        return view('barang', compact('items'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:255',
            'price_per_day' => 'required|numeric|min:0',
            'status' => 'required|in:tersedia,disewa',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video' => 'nullable|url|max:255',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('item-photos', 'public');
        }

        $validated['admin_id'] = Auth::id();

        Item::create($validated);

        return redirect()->route('barang')->with('success', 'Barang berhasil ditambahkan');
    }

    public function edit(Item $item)
    {
        return response()->json($item);
    }

    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:255',
            'price_per_day' => 'required|numeric|min:0',
            'status' => 'required|in:tersedia,disewa',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video' => 'nullable|url|max:255',
        ]);

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($item->photo) {
                Storage::disk('public')->delete($item->photo);
            }
            $validated['photo'] = $request->file('photo')->store('item-photos', 'public');
        }

        $item->update($validated);

        return redirect()->route('barang')->with('success', 'Barang berhasil diperbarui');
    }

    public function destroy(Item $item)
    {
        if ($item->photo) {
            Storage::disk('public')->delete($item->photo);
        }
        
        $item->delete();
        
        return redirect()->route('barang')->with('success', 'Barang berhasil dihapus');
    }
}