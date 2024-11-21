<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $ebook->title }}</title>

    <!-- Flipbook and Icons Stylesheet -->
    <link href="/assets/css/dflip.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/themify-icons.min.css" rel="stylesheet" type="text/css" />
</head>

<body style="background-color: maroon">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <!-- Normal Flipbook -->
                <div class="_df_book" id="book{{ $ebook->id }}" height="600" webgl="true"
                    backgroundcolor="maroon" source="{{ Storage::url($ebook->ebook_file_path) }}">

                </div>
            </div>
        </div>
    </div>

    <!-- Load jQuery first -->
    <script src="/assets/js/libs/jquery.min.js" type="text/javascript"></script>

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
