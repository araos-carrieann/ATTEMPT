<?php

namespace App\Http\Controllers;

use App\Models\eBooks;
use App\Models\ReadingList;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReadingListController extends Controller
{
    public function addToReadingList($e_book_id)
    {
        $user_id = Auth::user()->id;

        // Check if the user already has 10 active items in the reading list
        $readingListCount = ReadingList::where('user_id', $user_id)
            ->whereNull('deleted_at') // Exclude trashed items
            ->count();

        $limit = 1; // Adjust the limit as needed

        // Check if the eBook already exists for this user in the reading list
        $list = ReadingList::withTrashed()->where('user_id', $user_id)->where('e_book_id', $e_book_id)->first();

        if (!$list || $list->trashed()) {
            if ($readingListCount >= $limit) {
                Log::info("Limit reached");
                // Set the session flag to trigger the modal
                session()->flash('triggerModal', true);
                return response()->json(['message' => 'Not allowed'], 403); // Forbidden
            } else {
                if ($list->trashed()) {
                    // If the eBook is soft-deleted, restore it
                    $list->restore();
                    Log::info("Restored eBook ID: $e_book_id to the reading list.");
                    return response()->json(['message' => 'Restored to the reading list!'], 200);
                } else {
                    // If not already in the list, create a new reading list entry
                    ReadingList::create([
                        'user_id' => $user_id,
                        'e_book_id' => $e_book_id,
                    ]);
                    Log::info("Added eBook ID: $e_book_id to the reading list.");
                    return response()->json(['message' => 'Added to the reading list!'], 200);
                }
            }
        }
            // If it exists and is not soft-deleted, soft delete it
            $list->delete();
            Log::info("Removed eBook ID: $e_book_id from the reading list.");
            return response()->json(['message' => 'Removed from the reading list!'], 200);
    }


    public function checkReadingListStatus($e_book_id)
    {
        $user_id = Auth::user()->id;

        $isAddedToList = ReadingList::where('user_id', $user_id)
            ->where('e_book_id', $e_book_id)
            ->whereNull('deleted_at') // Only check non-soft-deleted
            ->exists();

        return response()->json(['isAddedToList' => $isAddedToList]);
    }


    public function userDashReadingList()
    {
        $user_id = Auth::id();  // You can directly use Auth::id() to get the user's ID

        // Get the list of eBook IDs for the user
        $userEbookList = ReadingList::where('user_id', $user_id)
            ->pluck('e_book_id');  // Use pluck to get an array of e_book_id values

        // Retrieve the eBooks based on the IDs in the reading list
        $ebookList = eBooks::whereIn('id', $userEbookList)->get();

        return view('dashboard', compact('ebookList'));
    }
}
