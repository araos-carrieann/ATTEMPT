<!-- resources/views/status.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Status</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .bg-maroon {
            background-color: #800000;
        }
        .text-maroon {
            color: #800000;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="max-w-lg mx-auto bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="bg-maroon text-white py-4 px-6">
            <h1 class="text-2xl font-semibold">Registration Status</h1>
        </div>
        <div class="p-6">
           
                <div class="text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01m-3.293-4.293a1 1 0 00-.293.707v4a1 1 0 00.293.707l6 6a1 1 0 001.414-1.414l-5.293-5.293V12a1 1 0 00-.293-.707L10.707 8.707A1 1 0 0012 8z"/>
                    </svg>
                    <h2 class="text-xl font-semibold text-gray-800 mt-4">Pending Approval</h2>
                    <p class="text-gray-600 mt-2">Your registration is still under review. Please check back later or contact support if you have any questions.</p>
                </div>
        <div class="bg-gray-100 py-4 px-6 text-center">
            <a href="{{ route('welcome') }}" class="text-maroon font-semibold hover:underline">Back to Home</a>
        </div>
    </div>
</body>
</html>
