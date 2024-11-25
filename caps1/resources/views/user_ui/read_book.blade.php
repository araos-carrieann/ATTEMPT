<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="/images/final_logo.png" sizes="48x48">

    <title>{{ $ebook->title }}</title>

    <!-- Flipbook and Icons Stylesheet -->
    <link href="/assets/css/dflip.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/themify-icons.min.css" rel="stylesheet" type="text/css" />
</head>

<body style="background-color: maroon">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <!-- Back Button -->
                <a href="javascript:history.back()" style="margin-bottom: 20px; display: inline-block; background-color: white; color: maroon; padding: 10px 20px; text-decoration: none;">
                    Back
                </a>
                <!-- Normal Flipbook -->
                <div class="_df_book" id="book{{ $ebook->id }}" height="600" webgl="true"
                    backgroundcolor="maroon" source="{{ Storage::url($ebook->ebook_file_path) }}">

                </div>
            </div>
        </div>
    </div>
    

    <!-- Load jQuery first -->
    <script src="/assets/js/libs/jquery.min.js" type="text/javascript"></script>
{{-- 
    <script>
        //Version less than 2.0
        jQuery(function() {

            DFLIP.defaults.onCreate = function(flipbook) {
                var page = localStorage.getItem(flipbook.options.id);
                if (page !== null) {
                    flipbook.options.openPage = page;
                    console.log("Flipbook : " + flipbook.options.id + " Restored at : " + flipbook.options
                        .openPage);
                }
            };

            DFLIP.defaults.onFlip = function(flipbook) {
                console.log("Flipbook : " + flipbook.options.id + "stored at : " + flipbook.target._activePage);
                localStorage.setItem(flipbook.options.id, flipbook.target._activePage);
            };

        });
    </script> --}}
    {{-- <script>
        jQuery(function() {
            DFLIP.defaults.onCreate = function(flipbook) {
                var eBookId = flipbook.options.id;
                $.ajax({
                    url: '/reading-progress/' + eBookId,
                    method: 'GET',
                    success: function(data) {
                        if (data && data.page) {
                            flipbook.options.openPage = data.page;
                            console.log("Flipbook : " + eBookId + " Restored at : " + flipbook.options.openPage);
                        }
                    }
                });
            };
    
            DFLIP.defaults.onFlip = function(flipbook) {
                var eBookId = flipbook.options.id;
                var currentPage = flipbook.target._activePage;
    
                $.ajax({
                    url: '/reading-progress',
                    method: 'POST',
                    data: {
                        e_book_id: eBookId,
                        page: currentPage,
                        _token: $('meta[name="csrf-token"]').attr('content') // CSRF token for Laravel
                    },
                    success: function(response) {
                        console.log("Flipbook : " + eBookId + " stored at : " + currentPage);
                    }
                });
            };
        });
    </script>
    
     --}} 

     <script>
        jQuery(function() {
            function extractInteger(eBookId) {
                // Extract the integer part of the ID using a regular expression
                return eBookId.match(/\d+/) ? parseInt(eBookId.match(/\d+/)[0], 10) : null;
            }
    
            // On initializing the flipbook
            DFLIP.defaults.onCreate = function(flipbook) {
                var eBookId = extractInteger(flipbook.options.id); // Extract integer ID
                
                if (!eBookId) {
                    console.error("Invalid eBook ID");
                    return;
                }
    
                var serverPageLoaded = false;
    
                if (navigator.onLine) {
                    $.ajax({
                        url: '/reading-progress/' + eBookId,
                        method: 'GET',
                        success: function(data) {
                            if (data && data.page) {
                                flipbook.options.openPage = data.page;
                                serverPageLoaded = true;
                                console.log("Flipbook : " + eBookId + " Restored from database at : " + flipbook.options.openPage);
                            } else {
                                console.log("No progress found in database for eBook : " + eBookId);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("Error fetching progress from database: ", error);
                        },
                        complete: function() {
                            if (!serverPageLoaded) {
                                var storedPage = localStorage.getItem("book" + eBookId);
                                if (storedPage !== null) {
                                    flipbook.options.openPage = storedPage;
                                    console.log("Flipbook : book" + eBookId + " Restored from localStorage at : " + flipbook.options.openPage);
                                }
                            }
                        }
                    });
                } else {
                    var storedPage = localStorage.getItem("book" + eBookId);
                    if (storedPage !== null) {
                        flipbook.options.openPage = storedPage;
                        console.log("Flipbook : book" + eBookId + " Restored from localStorage at : " + flipbook.options.openPage);
                    }
                }
            };
    
            // On flipping the page
            DFLIP.defaults.onFlip = function(flipbook) {
                var eBookId = extractInteger(flipbook.options.id); // Extract integer ID
                var currentPage = flipbook.target._activePage;
    
                localStorage.setItem("book" + eBookId, currentPage);
                console.log("Flipbook : book" + eBookId + " stored at (localStorage): " + currentPage);
    
                if (navigator.onLine) {
                    $.ajax({
                        url: '/reading-progress',
                        method: 'POST',
                        data: {
                            e_book_id: eBookId,
                            page: currentPage,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            console.log("Flipbook : " + eBookId + " stored at (database): " + currentPage);
                        },
                        error: function(xhr, status, error) {
                            console.error("Error syncing to database: ", error);
                        }
                    });
                }
            };
    
            // Sync localStorage to the database when the user comes online
            window.addEventListener('online', function() {
                console.log("User is back online. Syncing progress...");
                for (let key in localStorage) {
                    if (localStorage.hasOwnProperty(key) && key.startsWith("book")) {
                        var eBookId = extractInteger(key); // Extract integer ID
                        var currentPage = localStorage.getItem(key);
    
                        if (eBookId) {
                            $.ajax({
                                url: '/reading-progress',
                                method: 'POST',
                                data: {
                                    e_book_id: eBookId,
                                    page: currentPage,
                                    _token: $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function(response) {
                                    console.log("Flipbook : " + eBookId + " synced to database at : " + currentPage);
                                },
                                error: function(xhr, status, error) {
                                    console.error("Error syncing to database: ", error);
                                }
                            });
                        }
                    }
                }
            });
    
            // Sync on page unload
            window.addEventListener('beforeunload', function() {
                if (navigator.onLine) {
                    for (let key in localStorage) {
                        if (localStorage.hasOwnProperty(key) && key.startsWith("book")) {
                            var eBookId = extractInteger(key); // Extract integer ID
                            var currentPage = localStorage.getItem(key);
    
                            if (eBookId) {
                                $.ajax({
                                    url: '/reading-progress',
                                    method: 'POST',
                                    data: {
                                        e_book_id: eBookId,
                                        page: currentPage,
                                        _token: $('meta[name="csrf-token"]').attr('content')
                                    },
                                    success: function(response) {
                                        console.log("Flipbook : " + eBookId + " synced to database at : " + currentPage);
                                    },
                                    error: function(xhr, status, error) {
                                        console.error("Error syncing to database: ", error);
                                    }
                                });
                            }
                        }
                    }
                }
            });
        });
    </script>
    
    
    
    <!-- Load THREE.js before any scripts that depend on it -->
    <script src="/assets/js/libs/three.min.js" type="text/javascript"></script>

    <!-- Load other scripts after jQuery and THREE.js -->
    <script src="/assets/js/libs/compatibility.js" type="text/javascript"></script>
    <script src="/assets/js/libs/mockup.min.js" type="text/javascript"></script>
    <script src="/assets/js/libs/pdf.min.js" type="text/javascript"></script>
    <script src="/assets/js/libs/pdf.worker.min.js" type="text/javascript"></script>
    <script src="/assets/js/dflip.min.js" type="text/javascript"></script>

   

</body>

</html>
