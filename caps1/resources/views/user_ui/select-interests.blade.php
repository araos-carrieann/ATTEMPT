<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Your Favorite Categories</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #cce0ff, #d8aaff);
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .container {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
        }
        h3 {
            font-size: 1.5rem;
            color: #333;
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .category-boxes {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .category-box {
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 1rem;
            width: calc(50% - 1rem); /* Adjust to fit two boxes per row */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
        }
        .category-box input[type="checkbox"] {
            margin-right: 0.5rem;
            accent-color: #4a90e2;
        }
        .category-box label {
            font-size: 1rem;
            color: #555;
        }
        button {
            display: block;
            width: 100%;
            background: linear-gradient(to right, #4a90e2, #d25cfc);
            color: white;
            border: none;
            padding: 0.75rem;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
            margin-top: 1rem;
        }
        button:hover {
            background: linear-gradient(to right, #357abd, #c04dcb);
        }
        button:focus {
            outline: 2px solid #d25cfc;
            outline-offset: 2px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3>Select Your Favorite Categories</h3>

        <form method="POST" action="{{ route('interests.save') }}">
            @csrf

            <div class="category-boxes">
                @foreach($subcategories as $category)
                    <div class="category-box">
                        <input 
                            id="category-{{ $category->id }}" 
                            type="checkbox" 
                            name="categories[]" 
                            value="{{ $category->id }}"
                        >
                        <label for="category-{{ $category->id }}">{{ $category->name }}</label>
                    </div>
                @endforeach
            </div>

            <button type="submit">Submit Interests</button>
        </form>
    </div>
</body>
</html>
