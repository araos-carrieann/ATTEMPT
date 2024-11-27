<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Book Detail Page</title>
    @vite(['resources/css/app.css', 'resources/css/style.css', 'resources/css/book-filter.css', 'resources/js/app.js', 'resources/js/back-to-top.js', 'resources/js/increment-decrement.js', 'resources/js/jquery.counterup.min.js', 'resources/js/repeat-js.js', 'resources/js/script.js'])
    <link rel="icon" type="image/png" href="/images/final_logo.png" sizes="48x48">
    <!--- google font link-->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet" />
    <!-- Fontawesome Link for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="manifest" href="../favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <style>
        .star-rating {
            direction: rtl;
            display: inline-block;
        }

        .star-rating input {
            display: none;
            /* Hide radio buttons */
        }

        .star-rating label {
            font-size: 30px;
            color: gray;
            cursor: pointer;
            transition: color 0.2s;
        }

        /* Change color for the checked radio button and labels */
        .star-rating input:checked~label {
            color: gold;
        }

        /* Hover effect */
        .star-rating label:hover,
        .star-rating label:hover~label {
            color: gold;
        }

        /* Ensure that checked stars remain gold */
        .star-rating input:checked+label,
        .star-rating input:checked+label:hover,
        .star-rating input:checked+label:hover~label {
            color: gold;
        }

        .filled {
            color: gold;
            /* or any other color to fill the stars */
        }

        .stars {
            display: inline-block;
        }

        .stars i {
            color: gold;
            /* or any other color */
            margin-right: 2px;
        }


        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            resize: vertical;
        }

        button {
            background-color: #4CAF50;
            /* Green */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
            /* Darker green */
        }

        .reviewer-container {
            margin-top: 20px;
        }

        .reviews {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
        }

        .img-detail {
            display: flex;
            align-items: center;
        }

        .img-detail img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .name h5 {
            margin: 0;
        }

        .review-footer {
            margin-top: 10px;
        }

        .rating-star {
            color: gold;
        }
    </style>
</head>

<body>
    <header>
        <nav class="navbar" style="background-color: #f0daa7 ;">
            <div class="logo">
                <a href="{{ url('/') }}">
                    <div class="img">
                        <img src="/images/final_logo.png" alt="" />
                    </div>
                    <div class="logo-header">
                        <h4>LIBRARY
                </a></h4>
                <small>PUP UNISAN CAMPUS</small>
            </div>
            </div>
            <ul class="nav-list">
                <div class="logo">
                    <div class="title">
                        <div class="img">
                            <img src="images/final_logo.png" alt="" />
                        </div>
                        <div class="logo-header">
                            <h4><a href="index.html">LIBRARY</a></h4>
                            <small>PUP UNISAN CAMPUS</small>
                        </div>
                    </div>
                    <button class="close"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <li><a href="welcome.blade.php">Home</a></li>
                <livewire:welcome.navigation />
                <x-modal name="login-modal" :show="$errors->isNotEmpty()" maxWidth="sm" focusable>
                    <div>
                        <div class="bg-white p-6 rounded-lg shadow-lg w-full">
                            <livewire:pages.auth.login />
                        </div>
                    </div>
                </x-modal>
            </ul>
            <div class="hamburger">
                <div class="line"></div>
                <div class="line"></div>
                <div class="line"></div>
            </div>
        </nav>
    </header>

    <div class="breadcrumb-container">
        <ul class="breadcrumb">
            <li><a href="#">Home</a></li>
            <li><a href="#" style="color: #6c5dd4">Books</a></li>
            <li><a href="#">{{ $ebook_data->title }}</a></li>
        </ul>
    </div>

    <section class="book-overview">
        <div class="img">
            <img class="img" src="{{ Storage::url($ebook_data->book_cover) }}" />
        </div>

        <div class="book-content">
            <h4>{{ $ebook_data->title }}</h4>
            <div class="meta">
                <div class="review">
                    <div class="rating">
                        <div class="star">
                            <div class="stars">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= floor($computed_rating))
                                        <i class="fa-solid fa-star"></i> <!-- Full star -->
                                    @elseif ($i == ceil($computed_rating) && $computed_rating - floor($computed_rating) > 0)
                                        <i class="fa-solid fa-star-half-stroke"></i> <!-- Half star -->
                                    @else
                                        <i class="fa-regular fa-star"></i> <!-- Empty star -->
                                    @endif
                                @endfor
                                <small><span>{{ number_format($computed_rating, 1) }}</span> out of 5</small>
                            </div>
                        </div>
                    </div>
                    <div class="comment-like">
                        <small><i class="fa-solid fa-comments" style="color: #800000;"></i></i>
                            <span>{{ $number_of_reviews }}
                                Reviews</span></small>
                        <small><i class="fa-solid fa-heart" style="color: #800000;"></i>
                            <span>{{ $number_of_favorites }} Likes</span></small>
                    </div>
                </div>
            </div>
            <p>
                {{ $ebook_data->description }}
            </p>
            <div class="footer">
                <div class="author-detail">
                    <div class="author">
                        <small>Written by</small>
                        <strong> {{ $ebook_data->author }}</strong>
                    </div>
                    <div class="publisher">
                        <small>Publisher</small>
                        <strong>{{ $ebook_data->publisher }}</strong>
                    </div>
                    <div class="year">
                        <small>Year</small>
                        <strong> {{ $ebook_data->publication_year }}</strong>
                    </div>
                </div>

            </div>
            <div class="book-price">
                <div class="input-group">
                    <x-modal name="login-modal" :show="$errors->isNotEmpty()" maxWidth="sm" focusable>
                        <div>
                            <div class="bg-white p-6 rounded-lg shadow-lg w-full">
                                <livewire:pages.auth.login />
                            </div>
                        </div>
                    </x-modal>
                    @auth
                        <!-- User is authenticated, direct to route -->
                        <form action="{{ route('ebooks.read', ['slug' => $ebook_data->slug]) }}" method="GET">
                            <button type="submit" class="cartbtn">
                                <i class="fa-solid fa-book-open"></i> Read
                            </button>
                        </form>
                        <button class="like" data-id="{{ $ebook_data->id }}">
                            <i class="fa-regular fa-heart"></i>
                        </button>
                        <button class="reading-list" data-id="{{ $ebook_data->id }}" id="addlistbtn">
                            <i class="fa-regular fa-bookmark"></i>
                        </button>
                    @else
                        <x-danger-button x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'login-modal')"
                            style="background-color: #800000; color: white;" class="cartbtn">
                            <i class="fa-solid fa-book-open"></i>
                            {{ __('Read') }}
                        </x-danger-button>
                    @endauth
                </div>
            </div>
        </div>
    </section>
    <section class="book-info">
        <div class="detail-customer">
            <div class="tabbtns">
                <button class="tablink" data-btn="detail">Book Details</button>
                <button class="tablink" data-btn="customer">Customer Reviews</button>
            </div>
            <div class="book-detail tabcontent" id="detail">
                <div class="detail-line">
                    <strong>Book Title</strong><span>{{ $ebook_data->title }}</span>
                </div>
                <div class="detail-line">
                    <strong>Author</strong><span>{{ $ebook_data->author }}</span>
                </div>
                <div class="detail-line">
                    <strong>ISBN</strong><span>{{ $ebook_data->isbn }}</span>
                </div>
                <div class="detail-line">
                    <strong>Edition Language</strong>
                    <span>
                        @if ($ebook_data->language == 'en')
                            English
                        @elseif($ebook_data->language == 'ph')
                            Filipino
                        @else
                            {{ $ebook_data->language }} <!-- Fallback for other languages -->
                        @endif
                    </span>
                </div>

                <div class="detail-line">
                    <strong>Date Published</strong><span>{{ $ebook_data->publication_year }}</span>
                </div>
                <div class="detail-line">
                    <strong>Publisher</strong><span>{{ $ebook_data->publisher }}</span>
                </div>
                <div class="detail-line tag-line">
                    <strong>Tags</strong>
                    <div class="tags">
                        @foreach ($ebook_data->fronttags as $tag)
                            @php
                                // Decode the 'slug' field which contains the JSON data
                                $names = json_decode($tag->slug, true); // Decode the slug field

                                // Check for the 'en' key or use 'Unknown' if it doesn't exist
$displayName = $names['en'] ?? 'Unknown';
                            @endphp
                            <span>{{ $displayName }}</span>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="customer-review tabcontent" id="customer">
                <div class="rating">
                    <div class="rating-info">
                        <h5>Ratings</h5>
                    </div>
                    <div class="star">
                        <small><span>4.7</span>out of 5</small>
                        <div class="stars">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                        </div>
                    </div>
                </div>
                <strong>Showing 4 of 20 reviews</strong>
                @foreach ($ebook_reviews as $review)
                    <div class="reviewer-container">
                        <div class="reviews">
                            <div class="img-detail">
                                <i class="fa-solid fa-circle-user" style="font-size: 38px;"></i>

                                <div class="name">
                                    <h5>{{ $review->user->username }}</h5>
                                    <small>{{ $review->created_at->format('F j, Y') }}</small>
                                </div>
                            </div>
                            <div class="review-footer">
                                <p>{{ $review->review }}</p>
                                <div class="rating-star">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $review->rating)
                                            <i class="fa-solid fa-star filled"></i> <!-- Filled star -->
                                        @else
                                            <i class="fa-solid fa-star" style="color: #ccc"></i> <!-- Empty star -->
                                        @endif
                                    @endfor
                                    <span>{{ $review->rating }}</span>
                                </div>

                            </div>
                        </div>
                        <button>View More</button>
                    </div>
                @endforeach
                <div class="review-form">
                    <h5>Submit Your Review</h5>
                    <form action="{{ route('storeRev') }}" method="POST">
                        @csrf
                        <input type="hidden" id="e_book_id" name="e_book_id" value="{{ $ebook_data->id }}">
                        <!-- Replace with actual product ID -->
                        <div class="star-rating">
                            <input type="radio" id="star-5" name="rating" value="5" required>
                            <label for="star-5"><i class="fa-solid fa-star"></i></label>

                            <input type="radio" id="star-4" name="rating" value="4">
                            <label for="star-4"><i class="fa-solid fa-star"></i></label>

                            <input type="radio" id="star-3" name="rating" value="3">
                            <label for="star-3"><i class="fa-solid fa-star"></i></label>

                            <input type="radio" id="star-2" name="rating" value="2">
                            <label for="star-2"><i class="fa-solid fa-star"></i></label>

                            <input type="radio" id="star-1" name="rating" value="1">
                            <label for="star-1"><i class="fa-solid fa-star"></i></label>
                        </div>

                        <div>
                            <label for="review">Your Review:</label>
                            <textarea name="review" id="review" rows="4" required></textarea>
                        </div>

                        <button type="submit">Submit Review</button>
                    </form>
                </div>
            </div>
        </div>
    </section>


    @if ($recommInBookInfo->isNotEmpty())
        <section class="book-set">
            <div class="heading">
                <h4>Books Recommended</h4>
                <div class="arrowbtn">
                    <i id="left" class="fa-solid fa-angle-left"></i>
                    <i id="right" class="fa-solid fa-angle-right"></i>
                </div>
            </div>
            <div class="book-container">
                <div class="wrapper">
                    <ul class="carousel">
                        @foreach ($recommInBookInfo as $ebook)
                            <li class="card">
                                <div class="img">
                                    <img src="{{ Storage::url($ebook->book_cover) }}" />
                                </div>
                                <h5>{{ $ebook->title }}</h5>
                                <div class="genre">
                                    @if ($ebook->subcategories->isNotEmpty())
                                        <span>{{ $ebook->subcategories->first()->name }}</span>
                                    @endif
                                </div>

                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </section>
    @endif
    <section class="service">
        <div class="service-container">
            <div class="service-card">
                <div class="icon">
                    <i class="fa-solid fa-bolt-lightning"></i>
                </div>
                <div class="service-content">
                    <h5>Quick Delivery</h5>
                    <p>
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Id,
                        exercitationem.
                    </p>
                </div>
            </div>
            <div class="service-card">
                <div class="icon">
                    <i class="fa-solid fa-shield"></i>
                </div>
                <div class="service-content">
                    <h5>Secure Payment</h5>
                    <p>
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Id,
                        exercitationem.
                    </p>
                </div>
            </div>
            <div class="service-card">
                <div class="icon">
                    <i class="fa-solid fa-thumbs-up"></i>
                </div>
                <div class="service-content">
                    <h5>Best Quality</h5>
                    <p>
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Id,
                        exercitationem.
                    </p>
                </div>
            </div>
            <div class="service-card">
                <div class="icon">
                    <i class="fa-solid fa-star"></i>
                </div>
                <div class="service-content">
                    <h5>Return Guarantee</h5>
                    <p>
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Id,
                        exercitationem.
                    </p>
                </div>
            </div>
        </div>
    </section>
   
 @if(session('info'))
    <!-- Modal HTML Structure -->
    <div x-data="{ open: true }" x-init="if ($store.modal.open) open = true" x-show="open" @click.away="open = false" class="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-75 z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full z-50">
            <!-- Modal Header -->
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-semibold">Notice</h3>
                <button @click="open = false" class="text-gray-500">&times;</button>
            </div>
            <!-- Modal Body -->
            <div class="mt-4">
                <p>{{ session('info') }}</p>
            </div>
            <!-- Modal Footer -->
            <div class="mt-4 text-right">
                <button @click="open = false" style="background-color: #800000" class=" text-white px-4 py-2 rounded">Close</button>
            </div>
        </div>
    </div>
@endif

    <footer>
        <div class="container">
            <div class="logo-description">
                <div class="logo">
                    <div class="img">
                        <img src="images/final_logo.png" alt="" />
                    </div>
                    <div class="title">
                        <h4>LIBRARY</h4>
                        <small>UNISAN CAMPUS</small>
                    </div>
                </div>
                <div class="logo-body">
                    <p>
                        Lorem ipsum, dolor sit amet consectetur adipisicing elit. Magnam
                        voluptates eius quasi reiciendis recusandae provident veritatis
                        sequi, dolores architecto dolor possimus quos
                    </p>
                </div>
            </div>

            <div class="our-store list">
                <h4>PUP UNISAN LIBRARY</h4>
                <ul>
                    <li>
                        <a href=""><i class="fa-solid fa-location-dot"></i>Brgy. Kalilayan Unisan, Quezon
                    </li>
                    <li>
                        <a href=""><i class="fa-solid fa-phone"></i>+63 1123456781</a>
                    </li>
                    <li>
                        <a href=""><i class="fa-solid fa-envelope"></i>pupunisanlibrary@gmail.com</a>
                    </li>
                </ul>
            </div>
        </div>
    </footer>

    <button class="back-to-top"><i class="fa-solid fa-chevron-up"></i></button>

    <script>
        const tabbtn = document.querySelectorAll(".tablink");
        for (let i = 0; i < tabbtn.length; i++) {
            tabbtn[i].addEventListener('click', () => {
                let tabName = tabbtn[i].dataset.btn;
                let tabContent = document.getElementById(tabName);
                let AllTabContent = document.querySelectorAll(".tabcontent");
                let tabbtns = document.querySelectorAll(".tablink");
                for (let j = 0; j < AllTabContent.length; j++) {
                    AllTabContent[j].style.display = "none";
                }
                tabContent.style.display = "block";

            })

        }

        // Favorite button functionality
        document.querySelectorAll(".like").forEach((likeButton) => {
            let eBookId = likeButton.dataset.id;

            // Check if the eBook is already favorited when the page loads
            fetch(`/ebooks/favorite/status/${eBookId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.isFavorited) {
                        likeButton.classList.add("liked"); // Add "liked" UI feedback
                    }
                })
                .catch(error => console.error("Error:", error));

            // Click event to toggle favorites
            likeButton.addEventListener("click", () => {
                likeButton.classList.toggle("liked"); // Toggle "liked" UI feedback

                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch(`/ebooks/favorite/${eBookId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => {
                        if (response.ok) {
                            console.log("Updated favorites!");
                        } else {
                            return Promise.reject("Failed to update");
                        }
                    })
                    .catch(error => console.error("Error:", error));
            });
        });


        // Reading List button functionality
        document.querySelectorAll(".reading-list").forEach((listButton) => {
            let eBookId = listButton.dataset.id;

            // Check if the eBook is already in the reading list when the page loads
            fetch(`/reading-list/status/${eBookId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.isAddedToList) {
                        listButton.classList.add("in-list"); // Add "in-list" UI feedback
                    }
                })
                .catch(error => console.error("Error:", error));

            // Click event to add/remove from reading list
listButton.addEventListener("click", () => {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Send the request to add/remove from reading list
    fetch(`/reading-list/${eBookId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': token,
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            // Handle non-success responses
            if (response.status === 403) {
                // If forbidden (e.g., user doesn't have permission)
                console.log("You are not allowed to add this eBook to your reading list.");
                alert("You are not allowed to add this eBook to your reading list."); // Show alert or use a more user-friendly notification
                return Promise.reject("Failed to update: Unauthorized");
            } else {
                // For other non-success statuses, you can handle accordingly
                console.log("Failed to update reading list!");
                return Promise.reject("Failed to update: " + response.statusText);
            }
        }

        // If the request was successful, update the UI
        listButton.classList.toggle("in-list"); // Toggle "in-list" UI feedback
        console.log("Updated reading list!");
    })
    .catch(error => {
        // Handle errors
        console.error("Error:", error);
        // Optionally, revert the UI state if the operation fails
        listButton.classList.remove("in-list"); // Remove the class if there's an error
    });
});

        });
    </script>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
        integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="../js/repeat-js.js"></script>
    <script src="../js/increment-decrement.js"></script>
    <script src="../js/back-to-top.js"></script>
</body>

</html>
