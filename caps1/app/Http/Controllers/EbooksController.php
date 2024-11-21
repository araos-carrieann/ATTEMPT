<?php

namespace App\Http\Controllers;

use App\Models\BookView;
use App\Models\eBooks;
use App\Models\Review;
use App\Models\Subcategory;
use App\Models\UserInterest;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EbooksController extends Controller
{

    public function index()
    {
        $ebooks = eBooks::with(['reviews' => function ($query) {
            $query->select('e_book_id', DB::raw('SUM(rating) as total_rating'))
                  ->groupBy('e_book_id');
        }])->latest()->take(10)->get();

        // show books by category

        $ebooksBySubcategory = Subcategory::with('ebooks')->get()->mapWithKeys(function ($subcategory) {
            return [
                $subcategory->name => $subcategory->ebooks
            ];
        });

        $randomInterest = collect();
        $personalizedRecom = collect();
        $shownBooks = collect(); // Initialize a collection to track shown books

        if (Auth::check()) {
            $user = Auth::user();

            try {
                // Retrieve the names of the user's selected interests
                $interestNames = $user->interests->pluck('name');

                if ($interestNames->isEmpty()) {
                    return; // Exit if no interests are found
                }

                // Randomly pick one interest from the list
                $randomInterest = $interestNames->random();


                // Retrieve subcategories based on the selected interest
                $subcategories = Subcategory::whereHas('ebooks', function ($query) use ($randomInterest) {
                    $query->where('name', $randomInterest);
                })->with('ebooks')->get();

                foreach ($subcategories as $subcategory) {
                    $ebooks = $subcategory->ebooks->reject(function ($book) use ($shownBooks) {
                        if ($shownBooks->contains($book->id)) {
                            return true; // Book has already been shown, so exclude it
                        }
                        $shownBooks->push($book->id); // Track the book as shown
                        return false; // Include the book
                    });

                    // Add non-redundant books to personalized recommendations
                    if ($ebooks->isNotEmpty()) {
                        $personalizedRecom = $personalizedRecom->merge($ebooks);
                    }
                }

                if ($personalizedRecom->isEmpty()) {
                    // Handle the case where no recommended books are found if needed
                }
            } catch (\Exception $e) {
                // Handle exception if needed
            }
        }



        return view('welcome', compact('ebooks', 'ebooksBySubcategory', 'personalizedRecom', 'randomInterest'));
    }



    public function showInterestForm()
    {
        $subcategories = Subcategory::all();
        return view('user_ui.select-interests', compact('subcategories'));
    }


    public function storeInterests(Request $request)
    {
        // Find the user by email
        $user = Auth::user();

        // Validate the request to ensure that at least one category is selected
        $request->validate([
            'categories' => 'required|array',
        ]);

        // Iterate through the selected categories and save them to the database
        foreach ($request->input('categories') as $categoryId) {
            UserInterest::create([
                'user_id' => $user->id,  // Use the user's ID
                'subcategory_id' => $categoryId,
            ]);
        }

        // Redirect to home
        return redirect()->route('welcome');
    }


    public function books_info($id)
    {
        // Get all reviews for the eBook
        $com_rat = Review::where('e_book_id', $id)->get();
        
        // Calculate the number of reviews
        $number_of_reviews = $com_rat->count();
        
        // Compute the average rating, defaulting to 0 if there are no reviews
        $computed_rating = $number_of_reviews > 0 ? $com_rat->sum('rating') / $number_of_reviews : 0;
        
        // Fetch the eBook data
        $ebook_data = eBooks::findOrFail($id);
        
        // Fetch the reviews with the associated user data
        $ebook_reviews = Review::where('e_book_id', $id)->with('user')->get();
        
        return view('user_ui.book-info', compact('ebook_data', 'ebook_reviews', 'computed_rating', 'number_of_reviews'));
    }
    
    

    public function read($id)
    {

        if (Auth::user()) {
        
            $this->user_views($id);
            // Fetch the eBook
            try {
                $ebook = eBooks::findOrFail($id); // Use findOrFail to throw an error if not found
                Log::info("eBook found: ", $ebook->toArray()); // Log eBook details
            } catch (\Exception $e) {
                Log::error("eBook not found with ID: {$id}. Error: {$e->getMessage()}");
                return response()->json(['error' => 'eBook not found'], 404);
            }

            return view('user_ui.read_book', ['ebook' => $ebook]);
        } else {
            // Set session variable to open login modal
            session()->flash('showLoginModal', true);

            // Redirect to the previous page or a specific route
            return redirect()->back();
        }
    }

    public function user_views($id)
    {
        $user = Auth::user();
        $ebook_data = eBooks::findOrFail($id);
        $classification = $ebook_data->lcc_classification;
        $data = json_decode($classification, true);

        // Check if 'category_id' and 'subcategories' exist in the array
        $categoryId = $data['category_id'] ?? null; // Use null if 'category_id' is not present
        $firstSubcategory = isset($data['subcategories'][0]) ? $data['subcategories'][0] : null;
        BookView::create([
            'user_id' => $user->id,
            'e_book_id' => $ebook_data->id,
            'category' => $categoryId,
            'subcategory_letter' => $firstSubcategory,
            'year_level' => $user->year_level_id,
            'program' => $user->program_id,
        ]);
    }


    public function storeReviews(Request $request)
    {
        try {
            // Log that the storeReviews method has been triggered
            Log::info('storeReviews method triggered.', ['user_id' => Auth::user()->id]);
    
            // Validate the request
            $user_id = Auth::user()->id;
            $validatedData = $request->validate([
                'e_book_id' => 'required|integer',
                'rating' => 'required|integer|between:1,5',
                'review' => 'required|string|max:500',
            ]);
    
            // Log the validated data to make sure validation is correct
            Log::info('Validated data:', $validatedData);
    
            // Create a new review
            Review::create([
                'user_id' => $user_id,
                'e_book_id' => $request->e_book_id,
                'rating' => $request->rating,
                'review' => $request->review,
            ]);
    
            // Log successful creation of review
            Log::info('Review successfully created.', [
                'user_id' => $user_id,
                'e_book_id' => $request->e_book_id,
                'rating' => $request->rating,
            ]);
     
            return redirect()->back();
        } catch (\Exception $e) {
            // Log the exception message if something goes wrong
            Log::error('Error while storing review: ' . $e->getMessage());
            return redirect()->back()->withErrors('An error occurred while submitting your review.');
        }
    }
}
