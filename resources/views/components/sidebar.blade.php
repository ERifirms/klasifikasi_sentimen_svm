<aside class="w-full dark:bg-gray-800 bg-white/90 backdrop-blur-sm text-gray-800 min-h-screen shadow-lg">
    <nav class="space-y-4 py-6 px-4 w-full">

        <!-- Brand/Logo -->
        <div class="flex items-center justify-center py-4">
            <div class="bg-gray-50 rounded-full p-3 shadow-sm hover:shadow-md transition-shadow">
                <svg class="w-10 h-10 text-gray-800 dark:text-white" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 2a9 9 0 11-6.36 15.36L3 21l3.64-2.64A9 9 0 0112 2z"></path>
                </svg>
            </div>

            <div class="ml-3 text-center">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">SVM</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 italic">Sentimen Analisis</p>
            </div>
        </div>

        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}"
            class="flex items-center py-2 px-4 dark:text-white rounded-lg hover:bg-gray-100/80 transition-colors {{ request()->routeIs('dashboard') ? 'bg-gray-100/80' : '' }}">
            <svg class="w-5 h-5 text-gray-800 dark:text-white mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2 2 4-4 4 4 2-2"></path>
            </svg>
            Dashboard
        </a>

        <!-- Data Menu -->
        <div x-data="{ open: false }">
            <button @click="open = !open"
                class="flex items-center justify-between w-full dark:text-white py-2 px-4 rounded-lg hover:bg-gray-100/80 transition-colors">
                <span class="flex items-center">
                    <svg class="w-5 h-5 text-gray-800 dark:text-white mr-2" xmlns="http://www.w3.org/2000/svg"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4h16M4 8h16M4 12h16M4 16h16M4 20h16">
                        </path>
                    </svg>
                    Data
                </span>
                <svg class="w-5 h-5 text-gray-800 dark:text-white transform transition-transform"
                    :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <div x-show="open" class="ml-4 space-y-2 mt-2">
                <a href="{{ route('pages.data.latih') }}"
                    class="dark:text-white flex items-center py-2 px-4 rounded-lg hover:bg-gray-100/80 transition-colors {{ request()->routeIs('pages.data.latih') ? 'bg-gray-100/80' : '' }}">
                    <svg class="w-5 h-5 text-gray-800 dark:text-white mr-2" xmlns="http://www.w3.org/2000/svg"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12l-6 6-6-6"></path>
                    </svg>
                    Data Latih
                </a>
                <a href="{{ route('pages.data.uji') }}"
                    class="dark:text-white flex items-center py-2 px-4 rounded-lg hover:bg-gray-100/80 transition-colors {{ request()->routeIs('pages.data.uji') ? 'bg-gray-100/80' : '' }}">
                    <svg class="w-5 h-5 text-gray-800 dark:text-white mr-2" xmlns="http://www.w3.org/2000/svg"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12l-6 6-6-6"></path>
                    </svg>
                    Data Uji
                </a>
                <a href="{{ route('pages.data.proses') }}"
                    class="dark:text-white flex items-center py-2 px-4 rounded-lg hover:bg-gray-100/80 transition-colors {{ request()->routeIs('pages.data.proses') ? 'bg-gray-100/80' : '' }}">
                    <svg class="w-5 h-5 text-gray-800 dark:text-white mr-2" xmlns="http://www.w3.org/2000/svg"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12l-6 6-6-6"></path>
                    </svg>
                    Hasil Processing
                </a>
            </div>
        </div>

        <!-- Klasifikasi -->
        <a href="{{ route('klasifikasi') }}"
            class="dark:text-white flex items-center py-2 px-4 rounded-lg hover:bg-gray-100/80 transition-colors {{ request()->routeIs('klasifikasi') ? 'bg-gray-100/80' : '' }}">
            <svg class="w-5 h-5 text-gray-800 dark:text-white mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11V7h-4v4H3l9 9 9-9h-6z"></path>
            </svg>
            Klasifikasi
        </a>

        <!-- Hasil Analisis Menu -->
        <div x-data="{ open: false }">
            <button @click="open = !open"
                class="dark:text-white flex items-center justify-between w-full py-2 px-4 rounded-lg hover:bg-gray-100/80 transition-colors">
                <span class="flex items-center">
                    <svg class="w-5 h-5 text-gray-800 dark:text-white mr-2" xmlns="http://www.w3.org/2000/svg"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2 2 4-4 4 4 2-2"></path>
                    </svg>
                    Hasil Analisis
                </span>
                <svg class="  w-5 h-5 text-gray-800 dark:text-white transform transition-transform"
                    :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <div x-show="open" class="ml-4 space-y-2 mt-2">
                <a href="{{ route('pages.analisis.hasil-klasifikasi') }}"
                    class="dark:text-white flex items-center py-2 px-4 rounded-lg hover:bg-gray-100/80 transition-colors {{ request()->routeIs('pages.analisis.hasil-klasifikasi') ? 'bg-gray-100/80' : '' }}">
                    <svg class="w-5 h-5 text-gray-800 dark:text-white mr-2" xmlns="http://www.w3.org/2000/svg"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4 4h16M4 8h16M4 12h16M4 16h16M4 20h16"></path>
                    </svg>
                    Hasil Klasifikasi
                </a>
                <a href="{{ route('pages.analisis.tfidf') }}"
                    class="dark:text-white flex items-center py-2 px-4 rounded-lg hover:bg-gray-100/80 transition-colors {{ request()->routeIs('pages.analisis.tfidf') ? 'bg-gray-100/80' : '' }}">
                    <svg class="w-5 h-5 text-gray-800 dark:text-white mr-2" xmlns="http://www.w3.org/2000/svg"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4 4h16M4 8h16M4 12h16M4 16h16M4 20h16"></path>
                    </svg>
                    Hasil TF-IDF
                </a>
                <a href="{{ route('pages.analisis.wordcloud') }}"
                    class="dark:text-white flex items-center py-2 px-4 rounded-lg hover:bg-gray-100/80 transition-colors {{ request()->routeIs('pages.analisis.wordcloud') ? 'bg-gray-100/80' : '' }}">
                    <svg class="w-5 h-5 text-gray-800 dark:text-white mr-2" xmlns="http://www.w3.org/2000/svg"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4 4h16M4 8h16M4 12h16M4 16h16M4 20h16"></path>
                    </svg>
                    Word Cloud
                </a>
            </div>
        </div>
    </nav>
</aside>
