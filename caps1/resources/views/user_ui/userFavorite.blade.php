<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Favorites') }}
        </h2>
    </x-slot>

    <div class="py-12">
        
    
    <section class="filter">
        <div class="book-grid-container">
            <div class="book-collections">
                <h4>Books</h4>
                
                <div class="books">
                    
                    <div class="book-card">
                        @foreach ($favList as $ebook)
                            <div class="book-card">
                                <div class="img">
                                    <a href="book-detail.html"><img src="{{ Storage::url($ebook->book_cover) }}"
                                            alt="" /></a>
                                    <button class="like" id="likebtn">
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
</div>


    <script>  let likebtn = document.getElementsByClassName("like");
        for (let i = 0; i < likebtn.length; i++) {
          likebtn[i].addEventListener("click", () => {
            likebtn[i].classList.toggle("liked");
          });
        }</script>
</x-app-layout>
