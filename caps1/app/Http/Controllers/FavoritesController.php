<?php

namespace App\Http\Controllers;

use App\Models\eBooks;
use App\Models\User;
use App\Models\Favorites;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FavoritesController extends Controller
{
    public function addToFavorites($e_book_id)
    {
        $user_id = Auth::user()->id;

        // Check if the favorite already exists for this user and eBook
        $favorite = Favorites::withTrashed()->where('user_id', $user_id)->where('e_book_id', $e_book_id)->first();

        if ($favorite) {
            if ($favorite->trashed()) {
                // If the favorite is soft-deleted, restore it
                $favorite->restore();
                Log::info("Restored favorite eBook ID: $e_book_id");
                return response()->json(['message' => 'Restored to favorites!'], 200);
            } else {
                // If it exists and is not soft-deleted, soft delete it
                $favorite->delete();
                Log::info("Removed from favorites eBook ID: $e_book_id");
                return response()->json(['message' => 'Removed from favorites!'], 200);
            }
        } else {
            // Create a new favorite
            Favorites::create([
                'user_id' => $user_id,
                'e_book_id' => $e_book_id,
            ]);
            Log::info("Added to favorites eBook ID: $e_book_id");
            return response()->json(['message' => 'Added to favorites!'], 200);
        }
    }

    public function checkFavoriteStatus($e_book_id)
    {
        $user_id = Auth::user()->id;

        $isFavorited = Favorites::where('user_id', $user_id)
            ->where('e_book_id', $e_book_id)
            ->whereNull('deleted_at') // Only check non-soft-deleted
            ->exists();

        return response()->json(['isFavorited' => $isFavorited]);
    }

    public function userFavoriteList()
    {
        $user_id = Auth::id();  

        // Get the list of eBook IDs for the user
        $userFav = Favorites::where('user_id', $user_id)
            ->pluck('e_book_id'); 

        // Retrieve the eBooks based on the IDs in the reading list
        $favList = eBooks::whereIn('id', $userFav)->get();

        return view('dashboard', compact('favList'));
    }
}
