<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Home</title>
    @vite(['resources/css/app.css', 'resources/css/style.css', 'resources/js/app.js', 'resources/js/back-to-top.js', 'resources/js/increment-decrement.js', 'resources/js/jquery.counterup.min.js', 'resources/js/repeat-js.js', 'resources/js/script.js'])
    <!--- google font link-->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet" />
    <!-- Fontawesome Link for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <link rel="icon" type="image/png" href="/images/final_logo.png" sizes="48x48">
    <link rel="manifest" href="favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>


</head>

<body>
    <header>
        <nav class="navbar" style="background-color: #f0daa7 ;">
            <div class="logo">
                <div class="img">
                    <img src="/images/final_logo.png" alt="" />
                </div>
                <div class="logo-header">
                    <h4><a href="welcome.blade.php">LIBRARY</a></h4>
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
                <li><a href="pages/service.html">Service</a></li>
                <li><a href="pages/contact.html">Contact</a></li>
                <li><a href="Pages/book-filter.html">Books</a></li>
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
    <div>
        <livewire:search>
    </div>

    @if (Auth::check() && $interestRecommendations->isNotEmpty())
        <section class="book-set feature">
            <div class="heading">
                <h4>Because you like <strong>
                        {{ !empty($randomInterest) ? $randomInterest : 'No interests found.' }}
                    </strong>
                </h4>
                <div class="arrowbtn">
                    <i id="left" class="fa-solid fa-angle-left"></i>
                    <i id="right" class="fa-solid fa-angle-right"></i>
                </div>
            </div>
            <div class="book-container">
                <div class="wrapper">
                    <ul class="carousel">
                        @foreach ($interestRecommendations as $ebook)
                            <li class="card">
                                <div class="img">
                                    <a href="{{ route('ebooks.detail', $ebook->id) }}">
                                        <img src="{{ Storage::url($ebook->book_cover) }}"
                                            style="height: 200px; width:150px" />
                                    </a>
                                </div>
                                <h5 style="color: #ffffff">{{ $ebook->title }}</h5>
                                <div class="small-categ">
                                    @if ($ebook->subcategories->isNotEmpty())
                                        <small>{{ $ebook->subcategories->first()->name }}</small>
                                    @endif
                                </div>

                                <div class="footer">
                                    <span style="color: #ffffff" class="star">
                                        <i style="color: #F9E400" class="fa fa-star"></i>
                                        {{ optional($ebook->reviews->first())->total_rating ?? 0 }}
                                    </span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </section>
    @endif

    {{-- Lastviewed eBooks --}}
    @if (Auth::check() && $lastviewed_recommendations->isNotEmpty())
        <section class="book-set feature">
            <div class="heading">
                <h4>Since you read <strong>
                        {{ !empty($lastViewedBookName) ? $lastViewedBookName : 'No interests found.' }}
                    </strong>
                </h4>
                <div class="arrowbtn">
                    <i id="left" class="fa-solid fa-angle-left"></i>
                    <i id="right" class="fa-solid fa-angle-right"></i>
                </div>
            </div>
            <div class="book-container">
                <div class="wrapper">
                    <ul class="carousel">
                        @foreach ($lastviewed_recommendations as $ebook)
                            <li class="card">
                                <div class="img">
                                    <a href="{{ route('ebooks.detail', $ebook->id) }}">
                                        <img src="{{ Storage::url($ebook->book_cover) }}"
                                            style="height: 200px; width:150px" />
                                    </a>
                                </div>
                                <h5 style="color: #ffffff">{{ $ebook->title }}</h5>
                                <div class="small-categ">
                                    @if ($ebook->subcategories->isNotEmpty())
                                        <small>{{ $ebook->subcategories->first()->name }}</small>
                                    @endif
                                </div>

                                <div class="footer">
                                    <span style="color: #ffffff" class="star">
                                        <i style="color: #F9E400" class="fa fa-star"></i>
                                        {{ optional($ebook->reviews->first())->total_rating ?? 0 }}
                                    </span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </section>
    @endif


    {{-- Fav or reading List eBooks --}}
    @if (Auth::check() && $readingListFavRecommendations->isNotEmpty())
        <section class="book-set feature">
            <div class="heading">
                {{-- <h4>Since you read <strong>
                 {{ !empty($lastViewedBookName) ? $lastViewedBookName : 'No interests found.' }}
             </strong>
         </h4> --}}
                <div class="arrowbtn">
                    <i id="left" class="fa-solid fa-angle-left"></i>
                    <i id="right" class="fa-solid fa-angle-right"></i>
                </div>
            </div>
            <div class="book-container">
                <div class="wrapper">
                    <ul class="carousel">
                        @foreach ($readingListFavRecommendations as $ebook)
                            <li class="card">
                                <div class="img">
                                    <a href="{{ route('ebooks.detail', $ebook->id) }}">
                                        <img src="{{ Storage::url($ebook->book_cover) }}"
                                            style="height: 200px; width:150px" />
                                    </a>
                                </div>
                                <h5 style="color: #ffffff">{{ $ebook->title }}</h5>
                                <div class="small-categ">
                                    @if ($ebook->subcategories->isNotEmpty())
                                        <small>{{ $ebook->subcategories->first()->name }}</small>
                                    @endif
                                </div>

                                <div class="footer">
                                    <span style="color: #ffffff" class="star">
                                        <i style="color: #F9E400" class="fa fa-star"></i>
                                        {{ optional($ebook->reviews->first())->total_rating ?? 0 }}
                                    </span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </section>
    @endif


    {{-- Latest books --}}
    <section class="book-set">
        <div class="heading">
            <h4><strong>New Books</strong></h4>
            <div class="arrowbtn">
                <i id="left" class="fa-solid fa-angle-left"></i>
                <i id="right" class="fa-solid fa-angle-right"></i>
            </div>
        </div>
        <div class="book-container">
            <div class="wrapper">
                <ul class="carousel">
                    @foreach ($ebooks as $ebook)
                        <li class="card">
                            <div class="img">
                                <a href="{{ route('ebooks.detail', $ebook->id) }}">
                                    <img src="{{ Storage::url($ebook->book_cover) }}"
                                        style="height: 200px; width:150px" />
                                </a>
                            </div>
                            <h5>{{ $ebook->title }}</h5>
                            <div class="small-categ">
                                @if ($ebook->subcategories->isNotEmpty())
                                    <small>{{ $ebook->subcategories->first()->name }}</small>
                                    <!-- Display the first category name -->
                                @else
                                    <small>No category</small> <!-- Handle cases where there are no subcategories -->
                                @endif
                            </div>
                            <div class="footer">
                                <span class="star"><i class="fa fa-star"></i>
                                    {{ optional($ebook->reviews->first())->total_rating ?? 0 }}</span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </section>



    @foreach ($ebooksBySubcategory as $subcategoryName => $ebooks)
        <section class="book-set">
            <div class="heading">
                <h4><strong>{{ $subcategoryName }} Books</strong></h4>
                <div class="arrowbtn">
                    <i id="left" class="fa-solid fa-angle-left"></i>
                    <i id="right" class="fa-solid fa-angle-right"></i>
                </div>
            </div>
            <div class="book-container">
                <div class="wrapper">
                    <ul class="carousel">
                        @foreach ($ebooks as $ebook)
                            <li class="card">
                                <div class="img">
                                    <a href="{{ route('ebooks.detail', $ebook->id) }}">
                                        <img src="{{ Storage::url($ebook->book_cover) }}"
                                            style="height: 200px; width:150px;" />
                                    </a>
                                </div>
                                <h5>{{ $ebook->title }}</h5>
                                <div class="footer">
                                    <span class="star"><i class="fa fa-star"></i>
                                        {{ optional($ebook->reviews->first())->total_rating ?? 0 }}</span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </section>
    @endforeach

    <section class="countdown">
        <div class="container">
            <div class="customer counter">
                <div class="icon">
                    <i class="fa-solid fa-user-group"></i>
                </div>
                <div class="content">
                    <h4 class="count">{{ $number_of_user }}</h4>
                    <small>Active User</small>
                </div>
            </div>
            <div class="book counter">
                <div class="icon">
                    <i class="fa-solid fa-book"></i>
                </div>
                <div class="content">
                    <h4 class="count">{{ $number_of_ebooks }}</h4>
                    <small>Book Collections</small>
                </div>
            </div>
        </div>
    </section>

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
    <script src="js/back-to-top.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Counter-Up/1.0.0/jquery.counterup.min.js"></script>
    <script src="js/script.js"></script>
    <script src="js/repeat-js.js"></script>
    <script src="js/add-to-cart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.3.js"></script>

</body>

</html>
