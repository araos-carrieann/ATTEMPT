<?php

namespace App\Http\Controllers;

use App\Models\BookView;
use App\Models\eBooks;
use App\Models\Favorites;
use App\Models\ReadingList;
use App\Models\Review;
use App\Models\Subcategory;
use App\Models\User;
use App\Models\UserInterest;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EbooksController extends Controller
{
    public function interestRecommedation()
    {
        $interestRecom = collect();
        $shownBooks = collect(); // Initialize a collection to track shown books

        // Check if the user is authenticated
        if (Auth::check()) {
            $user = Auth::user();

            // Retrieve the names of the user's selected interests
            $interestNames = $user->interests->pluck('name');

            // Early return if no interests are found
            if ($interestNames->isEmpty()) {
                Log::info('No interest names found for user.');
                return $interestRecom; // Return an empty collection instead of continuing
            }

            Log::info('User Interest Names:', $interestNames->toArray());

            // Retrieve recommended books based on interests
            $subcategories = Subcategory::whereHas('ebooks', function ($query) use ($interestNames) {
                $query->whereIn('name', $interestNames);
            })->with('ebooks')->get();

            foreach ($subcategories as $subcategory) {
                // Reject already shown books to avoid duplicates
                $ebooks = $subcategory->ebooks->reject(function ($book) use ($shownBooks) {
                    if ($shownBooks->contains($book->id)) {
                        return true; // Exclude book if already shown
                    }
                    $shownBooks->push($book->id); // Track the book as shown
                    return false; // Include book
                });

                // Merge non-redundant books to personalized recommendations
                if ($ebooks->isNotEmpty()) {
                    $interestRecom = $interestRecom->merge($ebooks);
                }
            }
        }

        return $interestRecom;
    }



    public function lastViewedRecommendation()
    {
        $lastViewedReco = collect();
        $lastViewedBookName = null;

        // Check if the user is authenticated
        if (Auth::check()) {
            $user = Auth::user();

            // Fetch the last viewed eBook by the user
            $lastViewedEbook = BookView::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->first();

            //get the ebook based on view
            $eBook = $lastViewedEbook->ebook;

            // Get the first subcategory of the eBook (assuming only one subcategory exists)
            $subcategory = $eBook->subcategories->first();

            if ($subcategory) {
                // Store the name of the last viewed book
                $lastViewedBookName = $eBook->title;

                // Fetch related eBooks from the same subcategory, excluding the last viewed book
                $lastViewedReco = eBooks::whereHas('subcategories', function ($query) use ($subcategory) {
                    $query->where('subcategory_id', $subcategory->id); // Access the `id` of the first subcategory
                })
                    ->where('id', '!=', $eBook->id) // Exclude the last viewed eBook
                    ->inRandomOrder() // Order the results randomly
                    ->get();
            }
        }

        // Return the last viewed book name along with recommendations or `false` if no recommendations are available
        return [
            'last_viewed_book_name' => $lastViewedBookName,
            'lastviewed_recommendations' => $lastViewedReco,
        ];
    }



    public function readinglistFavRecommendation()
    {
        // Initialize the recommendation collection
        $readingFavRecommendation = collect();

        // Check if the user is logged in
        if (Auth::check()) {
            $user = Auth::user();

            // Randomly choose between reading list and favorite list for recommendations
            $randomReco = collect(['readingList', 'favoriteList'])->random();

            // Fetch the chosen recommendation (reading list or favorites)
            $pickReco = $randomReco === 'readingList'
                ? ReadingList::where('user_id', $user->id)
                ->with('eBook.subcategories')
                ->inRandomOrder()
                ->first()
                : Favorites::where('user_id', $user->id)
                ->with('eBook.subcategories')
                ->inRandomOrder()
                ->first();
               

            // Generate personalized recommendations based on the pick
            if ($pickReco) {
                $subcategory = $pickReco->ebook->subcategories->first();
                $relatedEbooks = eBooks::whereHas('subcategories', function ($query) use ($subcategory) {
                    $query->where('subcategory_id', $subcategory->id);
                })
                // ->where('id', '!=', $pickReco->id) // Exclude the last viewed eBook
                    ->inRandomOrder()
                    ->get();
                   
                $readingFavRecommendation = $relatedEbooks;
            }
        }

        // Return the recommendations or `false` if none are available
        return $readingFavRecommendation;
    }


    public function index()
    {
        // Fetch the latest 10 eBooks
        $ebooks = eBooks::latest()->take(10)->get();
        $number_of_user = User::where('status', 'active')->count();
        $number_of_ebooks = eBooks::count();
        // Group eBooks by subcategory
        $ebooksBySubcategory = Subcategory::with('ebooks')->get()->mapWithKeys(function ($subcategory) {
            return [
                $subcategory->name => $subcategory->ebooks
            ];
        });

        // Fetch recommendations using defined methods
        $interestRecommendations = $this->interestRecommedation();

        $lastViewedData = $this->lastViewedRecommendation();
        $lastViewedBookName = $lastViewedData['last_viewed_book_name'];
        $lastviewed_recommendations = $lastViewedData['lastviewed_recommendations'];

        // dd($lastviewed_recommendations);
        $readingListFavRecommendations = $this->readinglistFavRecommendation();


        // Return the view with all data
        return view('welcome', compact(
            'ebooks',
            'ebooksBySubcategory',
            'interestRecommendations',
            'lastViewedBookName',
            'lastviewed_recommendations',
            'readingListFavRecommendations',
            'number_of_user', 'number_of_ebooks'
        ));
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
        $number_of_favorites = Favorites::where('e_book_id', $id)->count();

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
        $recommInBookInfo = $this->recommendedInBookInfo($id);

        return view('user_ui.book-info', compact('ebook_data', 'ebook_reviews', 'computed_rating', 'number_of_reviews', 'number_of_favorites', 'recommInBookInfo'));
    }

    public function recommendedInBookInfo($id)
    {
        // Initialize an empty collection to hold the recommendations
        $recoBookInfo = collect();
    
        // Fetch the ebook by ID
        $openedEbook = eBooks::find($id);
    
        // If the ebook is found, proceed
        if ($openedEbook) {
            // Assuming you have a relationship called 'subcategories' on the eBooks model
            $subcategory = $openedEbook->subcategories->first(); // Get the first subcategory
    
            // If the ebook has a subcategory, fetch related ebooks based on the subcategory
            if ($subcategory) {
                $relatedEbooks = eBooks::whereHas('subcategories', function ($query) use ($subcategory) {
                    $query->where('subcategory_id', $subcategory->id);
                })
                ->where('id', '!=', $openedEbook->id)  // Exclude the current ebook
                ->inRandomOrder()
                ->get();
    
                // Store the related ebooks in the collection
                $recoBookInfo = $relatedEbooks;
            }
        }
    
        // Return the recommendations
        return $recoBookInfo;
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
