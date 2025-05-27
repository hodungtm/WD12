<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $search = $request->input('search');

    $wishlists = \App\Models\Wishlist::with(['user', 'product'])
        ->when($search, function($query, $search) {
            $query->whereHas('product', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })
            ->orWhereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        })
        ->paginate(10);

    return view('admin.wishlists.index', compact('wishlists', 'search'));
}

   
    public function show(string $id)
    {
        $wishlist = Wishlist::with(['user', 'product'])->findOrFail($id);

        return view('Admin.wishlists.show', compact('wishlist'));
    }
    public function destroy(string $id)
    {
        $wishlist = Wishlist::findOrFail($id);  
        $wishlist->delete();

        return redirect()->route('wishlists.index')->with('success', 'Xóa thành công!');

    }
}
