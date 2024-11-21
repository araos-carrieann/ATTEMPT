<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReadingProgress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReadingProgressController extends Controller
{
    /**
     * Fetch the reading progress for a user and an eBook.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPage(Request $request)
    {
        $userId = Auth::id();
        Log::info("Fetching page for user ID: $userId");

        // Fetch the saved page number for the specified e_book_id
        $page = ReadingProgress::where('user_id', $userId)
            ->where('e_book_id', $request->e_book_id)
            ->value('page');

        Log::info("The page is: $page for eBook ID: " . $request->e_book_id);
        return response()->json(['page' => $page]);
    }

    /**
     * Save the reading progress for a user and an eBook.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function savePage(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'e_book_id' => 'required|exists:e_books,id', // Assuming you have an e_books table
            'page' => 'required|integer',
        ]);

        // Log the request data for debugging
        Log::info("Saving page for eBook ID: " . $request->e_book_id . " and page: " . $request->page);

        // Save the current page number for the specified e_book_id
        ReadingProgress::updateOrCreate(
            ['user_id' => Auth::id(), 'e_book_id' => $request->e_book_id],
            ['page' => $request->page]
        );

        return response()->json(['success' => true]);
    }

    /**
     * Clear all saved page data for the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function clearPageData()
    {
        // Clear all saved page data for the authenticated user
        ReadingProgress::where('user_id', Auth::id())->delete();
        return response()->json(['success' => true]);
    }
}