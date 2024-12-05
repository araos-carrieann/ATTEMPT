<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Reading List') }}
        </h2>
    </x-slot>

    {{-- <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div> --}}
    @if (request()->routeIs('userDashboard'))
        <section class="filter">
            <div class="book-grid-container">
                <div class="book-collections">
                    <h4>Books</h4>
                    <div class="books">
                        @foreach ($ebookList as $ebook)
                            <div class="book-card">
                                <div class="img">
                                    <a href="{{ route('ebooks.read', $ebook->slug) }}">
                                        <img src="{{ Storage::url($ebook->book_cover) }}" alt="" /></a>
                                    <button class="reading-list" id="listbtn" data-id="{{ $ebook->id }}">
                                        <i class="fa-regular fa-bookmark"></i>
                                    </button>
                                </div>
                                <h5>{{ $ebook->title }}</h5>
                                @if ($ebook->subcategories->isNotEmpty())
                                    <small>{{ $ebook->subcategories->first()->name }}</small>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            </div>
        </section>
    @endif

    {{-- For Favorites --}}
    @if (request()->routeIs('userFavorite'))
        <section class="filter">
            <div class="book-grid-container">
                <div class="book-collections">
                    <div class="books">
                        @foreach ($favList as $ebook)
                            <div class="book-card">
                                <div class="img">
                                    <a href="{{ route('ebooks.read', $ebook->slug) }}">
                                        <img src="{{ Storage::url($ebook->book_cover) }}" alt="" /></a>
                                    <button class="liked" id="listbtn" data-id="{{ $ebook->id }}">
                                        <i class="fa-regular fa-heart"></i>
                                    </button>
                                </div>
                                <h5>{{ $ebook->title }}</h5>
                                @if ($ebook->subcategories->isNotEmpty())
                                    <small>{{ $ebook->subcategories->first()->name }}</small>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            </div>
        </section>
    @endif


    <script>
        // Favorite button functionality
        document.querySelectorAll(".liked").forEach((likeButton) => {
            let eBookId = likeButton.dataset.id;

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
                            window.location.reload();
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
                listButton.classList.toggle("in-list"); // Toggle "in-list" UI feedback

                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch(`/reading-list/${eBookId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => {
                        if (response.ok) {
                            window.location.reload();
                            console.log("Updated reading list!");
                        } else {
                            return Promise.reject("Failed to update");
                        }
                    })
                    .catch(error => console.error("Error:", error));
            });
        });
    </script>
</x-app-layout>
