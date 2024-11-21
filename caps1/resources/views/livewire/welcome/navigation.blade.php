<nav class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-4"> <!-- Responsive layout -->
    @auth
        @if (auth()->user()->role === 'ADMIN')
            <a href="{{ url('/admin') }}"
                class="flex items-center rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                <i class="fa-solid fa-circle-user fa-3x"></i>
            </a>
        @else
        <a href="{{ route('userDashboard') }}"
                class="flex items-center rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                <i class="fa-solid fa-circle-user fa-3x"></i>
            </a>
        @endif
    @else
        <x-danger-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'login-modal')"
            class="flex items-center space-x-2 ml-4" style="background-color: #800000; color: white;">
            {{ __('Login') }}
        </x-danger-button>

     
            <a href="{{ url('/admin/register') }}"
                class="flex items-center ml-4 px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                <i class="fa-solid fa-user-plus"></i>
                {{ __('Register') }}
            </a>
      
    @endauth
</nav>
