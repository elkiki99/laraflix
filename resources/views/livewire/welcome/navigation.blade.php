<nav class="flex justify-end flex-1 -mx-3">
    @auth
        <a href="{{ url('/home') }}"
            class="px-3 py-2 text-white transition rounded-md ring-1 ring-transparent focus:outline-none hover:text-white/80 focus-visible:ring-white">
            Home
        </a>
    @else
        <a href="{{ route('login') }}"
            class="px-3 py-2 text-white transition rounded-md ring-1 ring-transparent focus:outline-none hover:text-white/80 focus-visible:ring-white">
            Log in
        </a>

        @if (Route::has('register'))
            <a href="{{ route('register') }}"
                class="px-3 py-2 text-white transition rounded-md ring-1 ring-transparent focus:outline-none hover:text-white/80 focus-visible:ring-white">
                Register
            </a>
        @endif
    @endauth
</nav>
