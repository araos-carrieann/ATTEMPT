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

    <link rel="apple-touch-icon" sizes="57x57" href="favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
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
                    <img src="/images/logo1.png" alt="" />
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
                            <img src="images/logo.png" alt="" />
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

    @if (Auth::check())
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
                        @foreach ($personalizedRecom as $ebook)
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
                                        <i style="color: #F9E400" class="fa fa-star"></i> {{ optional($ebook->reviews->first())->total_rating ?? 0 }}
                                    </span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="arrowbtn">
                <i id="left" class="fa-solid fa-angle-left"></i>
                <i id="right" class="fa-solid fa-angle-right"></i>
              </div>
        </section>
    @endif


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
                                <span class="star"><i class="fa fa-star"></i> {{ optional($ebook->reviews->first())->total_rating ?? 0 }}</span>
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
                                    <span class="star"><i class="fa fa-star"></i> {{ optional($ebook->reviews->first())->total_rating ?? 0 }}</span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </section>
    @endforeach



    <section class="news">
        <div class="heading">
            <div class="title">
                <h4>Latest News</h4>
                <p>
                    Lorem ipsum dolor sit, amet consectetur adipisicing elit. <br />
                    Quia, laborum ad perspiciatis ab, sequi.
                </p>
            </div>
            <div class="btn">
                <button>View More <i class="fa-solid fa-arrow-right"></i></button>
            </div>
        </div>
        <div class="news-container">
            <div class="post">
                <div class="img">
                    <img src="images/news-1.avif" alt="" />
                </div>
                <h5>Why reading is important for our children?</h5>
                <p>
                    Lorem ipsum dolor sit, amet consectetur adipisicing elit.
                    Aspernatur, quo temporibus! Tenetur...
                    <a href="">Continue reading</a>
                </p>
                <div class="post-footer">
                    <div class="img">
                        <img src="images/man1.png" alt="" />
                    </div>
                    <div class="details">
                        <strong>James Bond</strong>
                        <small>5 August 2020</small>
                    </div>
                </div>
            </div>
            <div class="post">
                <div class="img">
                    <img src="images/news-2.webp" alt="" />
                </div>
                <h5>Why reading is important for our children?</h5>
                <p>
                    Lorem ipsum dolor sit, amet consectetur adipisicing elit.
                    Aspernatur, quo temporibus! Tenetur...
                    <a href="">Continue reading</a>
                </p>
                <div class="post-footer">
                    <div class="img">
                        <img src="images/man1.png" alt="" />
                    </div>
                    <div class="details">
                        <strong>James Bond</strong>
                        <small>5 August 2020</small>
                    </div>
                </div>
            </div>
            <div class="post">
                <div class="img">
                    <img src="images/news-3.jpg" alt="" />
                </div>
                <h5>Why reading is important for our children?</h5>
                <p>
                    Lorem ipsum dolor sit, amet consectetur adipisicing elit.
                    Aspernatur, quo temporibus! Tenetur...
                    <a href="">Continue reading</a>
                </p>
                <div class="post-footer">
                    <div class="img">
                        <img src="images/man1.png" alt="" />
                    </div>
                    <div class="details">
                        <strong>James Bond</strong>
                        <small>5 August 2020</small>
                    </div>
                </div>
            </div>
            <div class="post">
                <div class="img">
                    <img src="images/news-4.jpg" alt="" />
                </div>
                <h5>Why reading is important for our children?</h5>
                <p>
                    Lorem ipsum dolor sit, amet consectetur adipisicing elit.
                    Aspernatur, quo temporibus! Tenetur...
                    <a href="">Continue reading</a>
                </p>
                <div class="post-footer">
                    <div class="img">
                        <img src="images/man1.png" alt="" />
                    </div>
                    <div class="details">
                        <strong>James Bond</strong>
                        <small>5 August 2020</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="countdown">
        <div class="container">
            <div class="customer counter">
                <div class="icon">
                    <i class="fa-solid fa-user-group"></i>
                </div>
                <div class="content">
                    <h4 class="count">125,663</h4>
                    <small>Happy Customers</small>
                </div>
            </div>
            <div class="book counter">
                <div class="icon">
                    <i class="fa-solid fa-book"></i>
                </div>
                <div class="content">
                    <h4 class="count">50,672</h4>
                    <small>Book Collections</small>
                </div>
            </div>
            <div class="store counter">
                <div class="icon">
                    <i class="fa-solid fa-store"></i>
                </div>
                <div class="content">
                    <h4 class="count">1,562</h4>
                    <small>Our Stores</small>
                </div>
            </div>
            <div class="writer counter">
                <div class="icon">
                    <i class="fa-solid fa-feather"></i>
                </div>
                <div class="content">
                    <h4 class="count">457</h4>
                    <small>Famous Writer</small>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="logo-description">
                <div class="logo">
                    <div class="img">
                        <img src="images/logo.png" alt="" />
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
                <div class="social-links">
                    <h4>Follow Us</h4>
                    <ul class="links">
                        <li>
                            <a href=""><i class="fa-brands fa-facebook-f"></i></a>
                        </li>
                        <li>
                            <a href=""><i class="fa-brands fa-youtube"></i></a>
                        </li>
                        <li>
                            <a href=""><i class="fa-brands fa-twitter"></i></a>
                        </li>
                        <li>
                            <a href=""><i class="fa-brands fa-linkedin"></i></a>
                        </li>
                        <li>
                            <a href=""><i class="fa-brands fa-instagram"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="categories list">
                <h4>Book Categories</h4>
                <ul>
                    <li><a href="Pages/book-filter.html">Action</a></li>
                    <li><a href="Pages/book-filter.html">Adventure</a></li>
                    <li><a href="Pages/book-filter.html">Comedy</a></li>
                    <li><a href="Pages/book-filter.html">Crime</a></li>
                    <li><a href="Pages/book-filter.html">Drama</a></li>
                    <li><a href="Pages/book-filter.html">Fantasy</a></li>
                    <li><a href="Pages/book-filter.html">Horror</a></li>
                </ul>
            </div>
            <div class="quick-links list">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="index.html">About Us</a></li>
                    <li><a href="pages/contact.html">Contact Us</a></li>
                    <li><a href="pages/login.html">Login</a></li>
                    <li><a href="pages/registration.html">Sign Up</a></li>
                </ul>
            </div>
            <div class="our-store list">
                <h4>Our Store</h4>

                <ul>
                    <li>
                        <a href=""><i class="fa-solid fa-location-dot"></i>Brgy. Kalilayan Unisan, Quezon
                    </li>
                    <li>
                        <a href=""><i class="fa-solid fa-phone"></i>+63 1123456781</a>
                    </li>
                    <li>
                        <a href=""><i class="fa-solid fa-envelope"></i>support@library.id</a>
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
