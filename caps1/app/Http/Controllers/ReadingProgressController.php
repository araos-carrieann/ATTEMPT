<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReadingProgress;
use Illuminate\Support\Facades\Auth;

class ReadingProgressController extends Controller
{
    // Store reading progress
    public function store(Request $request)
    {
        $userId = Auth::id();
        $eBookId = $request->e_book_id;
        $page = $request->page;

        // Check if progress exists, then update or create new entry
        ReadingProgress::updateOrCreate(
            ['user_id' => $userId, 'e_book_id' => $eBookId],
            ['page' => $page]
        );

        return response()->json(['message' => 'Reading progress saved successfully']);
    }

    // Get reading progress
    public function getProgress($eBookId)
    {
        $userId = Auth::id();

        $progress = ReadingProgress::where('user_id', $userId)
            ->where('e_book_id', $eBookId)
            ->first();

        return response()->json($progress);
    }
}
