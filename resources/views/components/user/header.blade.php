<header class="text-gray-600 body-font bg-blue-200">
    <div class="container mx-auto flex flex-wrap p-5 flex-col md:flex-row items-center">
        <a href="{{ route('admin.top') }}" class="flex title-font font-medium items-center text-gray-900 mb-4 md:mb-0 cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-10 h-10 text-white p-2 bg-yellow-500 rounded-full" viewBox="0 0 24 24">
            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
            </svg>
            <span class="ml-3 text-xl">ようこそ</span>
        </a>
        <button
        {{-- 献立編集画面に遷移 --}}
        onclick="location.href='{{ route('user.dishes') }}'"
        class="flex ml-auto text-white bg-gray-700 border-0 py-2 px-6 focus:outline-none hover:bg-gray-900 rounded">
        献立候補登録
        </button>
        <form method="POST" action="{{ route('logout') }}" class='md:ml-auto'>
        @csrf
            <button
                :href="route('logout')"
                onclick="event.preventDefault();
                            this.closest('form').submit();"
                class="inline-flex items-center text-white bg-gray-700 border-0 py-1 px-3 focus:outline-none hover:bg-gray-800 rounded text-base mt-4 md:mt-0">
                Logout
            </button>
        </form>
    </div>
</header>
