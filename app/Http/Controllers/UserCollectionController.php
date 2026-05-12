<?php

namespace App\Http\Controllers;

use App\Models\Landmark;
use Illuminate\Http\Request;

class UserCollectionController extends Controller
{
    public function toggleFavorite(Request $request, $id)
    {
        $user = $request->user();
        $landmark = Landmark::findOrFail($id);

        if ($user->favorites()->where('landmark_id', $landmark->id)->exists()) {
            $user->favorites()->detach($landmark->id);
            return response()->json(['status' => 'removed']);
        } else {
            $user->favorites()->attach($landmark->id);
            return response()->json(['status' => 'added']);
        }
    }

    public function toggleWishlist(Request $request, $id)
    {
        $user = $request->user();
        $landmark = Landmark::findOrFail($id);

        if ($user->wishlists()->where('landmark_id', $landmark->id)->exists()) {
            $user->wishlists()->detach($landmark->id);
            return response()->json(['status' => 'removed']);
        } else {
            $user->wishlists()->attach($landmark->id);
            return response()->json(['status' => 'added']);
        }
    }

    public function getCollections(Request $request)
    {
        $user = $request->user();
        $favorites = $user->favorites;
        $wishlists = $user->wishlists;

        return response()->json([
            'favorites' => $favorites,
            'wishlists' => $wishlists,
        ]);
    }
}
