<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EbooksController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\ReadingListController;
use App\Http\Controllers\ReadingProgressController;
use App\Http\Controllers\UserController;
use App\Models\ReadingList;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use Illuminate\Notifications\Notification as BaseNotification;

Route::get('/customnotif', function () {
    // Retrieve registration data from session
    $registrationData = session('registrationData', []);

    if ($registrationData) {
        // Get all admin users
        $admins = User::where('role', 'ADMIN')->get();
        // Adjust the query according to your admin identification logic

        // Create an anonymous notification class with registration data
        $notification = new class($registrationData) extends BaseNotification {
            protected $registrationData;

            // Constructor to accept the registration data
            public function __construct($registrationData)
            {
                $this->registrationData = $registrationData;
            }

            public function via($notifiable)
            {
                return ['database'];
            }

            public function toDatabase($notifiable)
            {
                return [
                    'title' => 'New User Registration: ' . $this->registrationData['first_name'],
                    'body' => 'A new user has registered with the email: ' . $this->registrationData['email'],
                    'registration_data' => $this->registrationData, // Optional: Include full registration data
                ];
            }
        };

        // Send the notification to all admins
        Notification::send($admins, $notification);
    }
    return redirect()->route('user_ui.status');
    
})->name('customnotif');

Route::get('/decide', [EbooksController::class, 'routedecider'])->name('decide');

Route::get('/custom-page', [EbooksController::class, 'index'])->name('custom.page.route');

Route::get('/', [EbooksController::class, 'index'])->name('welcome');

Route::get('/userdash', [ReadingListController::class, 'userDashReadingList'])->name('userDashboard');
Route::get('/userFav', [FavoritesController::class, 'userFavoriteList'])->name('userFavorite');

Route::get('/status', [UserController::class, 'show'])->name('user_ui.status');

Route::get('/ebooks/read/{id}', [EbooksController::class, 'read'])->name('ebooks.read');
Route::get('/ebooks/{id}', [EbooksController::class, 'books_info'])->name('ebooks.detail');
Route::post('/reviews/store', [EbooksController::class, 'storeReviews'])->name('storeRev');
Route::middleware('auth')->group(function () {
    Route::get('/navigation', [Controller::class, 'index']);
});
Route::post('/interests', [EbooksController::class, 'storeInterests'])->name('interests.save');
Route::get('/select-interests', [EbooksController::class, 'showInterestForm'])->name('interests.create');


Route::post('/save-last-read-page', [ReadingProgressController::class, 'store'])
    ->middleware('auth');

    Route::post('/reading-progress', [ReadingProgressController::class, 'store'])->middleware('auth');
    Route::get('/reading-progress/{eBookId}', [ReadingProgressController::class, 'getProgress'])->middleware('auth');


Route::post('/ebooks/favorite/{id}', [FavoritesController::class, 'addToFavorites'])->name('ebooks.favorite');
Route::get('/ebooks/favorite/status/{id}', [FavoritesController::class, 'checkFavoriteStatus'])->name('ebooks.favorite.status');

Route::post('/reading-list/{e_book_id}', [ReadingListController::class, 'addToReadingList'])->name('reading.list.add'); // Add or toggle eBook in reading list
    Route::get('/reading-list/status/{e_book_id}', [ReadingListController::class, 'checkReadingListStatus'])->name('reading.list.status'); // Check if eBook is in reading list

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');
    Route::get('/login', function () {
        return redirect()->route('welcome'); 
    });
require __DIR__ . '/auth.php';
