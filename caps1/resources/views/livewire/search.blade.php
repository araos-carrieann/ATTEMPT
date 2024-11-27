<div class="relative">
    <div class="flex justify-center items-center">
        <div class="relative w-96 max-w-lg px-1 pt-1">
            <input wire:model.live.throttle.150ms="search" type="text"
                class="block w-full flex-1 py-2 px-3 mt-2 rounded-md bg-slate-100" placeholder="Search..." />
        </div>
    </div>
    @if (!empty($search) && count($results) > 0)
        <div class="search-results bg-white rounded-md shadow-lg mt-2">
            <section class="book-sale mt-4">
                <div class="heading flex items-center justify-between px-3 py-2 border-b">
                    <h4 class="text-lg font-semibold">Search Books</h4>
                    <div class="arrowbtn flex space-x-2">
                        <i id="left" class="fa-solid fa-angle-left cursor-pointer"></i>
                        <i id="right" class="fa-solid fa-angle-right cursor-pointer"></i>
                    </div>
                </div> 

                <div class="book-container mt-2 px-3">
                    <div class="wrapper">
                        <ul class="carousel list-none p-0 m-0 flex overflow-x-auto">
                            @foreach ($results as $ebook)
                                <li class="card flex-none w-40 mr-2 rounded-lg overflow-hidden">
                                    <div class="img">
                                        <a href="{{ route('ebooks.detail', $ebook['id']) }}">
                                            <img src="{{ Storage::url($ebook['book_cover']) }}" alt="{{ $ebook['title'] }}"
                                            style="height: 200px; width:150px" >
                                        </a>
                                    </div>
                                    <div class="p-2">
                                        <h5 class="text-sm font-medium">{{ $ebook['title'] }}</h5>
                                        <div class="small-categ">
                                            @if ($ebook->subcategories->isNotEmpty())
                                                <small>{{ $ebook->subcategories->first()->name }}</small>
                                            @endif
                                        </div>
                                        
                                        <div class="footer mt-2 text-xs text-gray-500">
                                            <span class="star"><i class="fa fa-star"></i> 4.7</span>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </section>
        </div>
    @endif
</div>
