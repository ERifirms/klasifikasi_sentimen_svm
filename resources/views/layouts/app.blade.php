<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }} " class="{{ session('theme') == 'dark' ? 'dark' : '' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'nita') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/wordcloud@1.0.7/wordcloud.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const toggle = document.getElementById("dark-mode-toggle");
            const html = document.documentElement; // Pastikan perubahan ada di <html>

            // Cek mode sebelumnya
            if (localStorage.getItem("theme") === "dark") {
                html.classList.add("dark");
            }

            toggle.addEventListener("click", function() {
                if (html.classList.contains("dark")) {
                    html.classList.remove("dark");
                    localStorage.setItem("theme", "light");
                } else {
                    html.classList.add("dark");
                    localStorage.setItem("theme", "dark");
                }
            });
        });
    </script>

    <!-- Custom Styles -->
    <style>
        /* Reset untuk menghapus margin default */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Struktur dasar */
        html,
        body {
            height: 100%;
            overflow: hidden;
        }

        body {
            display: flex;
            flex-direction: row;
            min-height: 100vh;
            /* Pastikan body memiliki tinggi penuh */
        }

        .sidebar {
            width: 20%;
            display: flex;
            /* box-shadow: 20px #F9FAFB; */
        }

        .main-content {
            flex-grow: 1;
            overflow-y: auto;
            background-color: #F9FAFB;
            height: 100vh;
            width: 80%;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 20%;
                /* Sidebar tetap 20% */
            }

            .main-content {
                width: 80%;
                /* Konten utama mengisi 80% lebar */
            }
        }
    </style>
</head>

<body class="font-sans antialiased">
    <!-- Sidebar -->

    <body class="bg-white text-gray-900 dark:bg-gray-900 dark:text-gray-100">
        <div class="sidebar bg-gray-100 dark:bg-gray-800 text-black dark:text-white">
            <x-sidebar />
        </div>

        <div class="main-content bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
            <div class="sticky top-0 z-50 bg-gray-100 dark:bg-gray-800 shadow-md">
                @include('layouts.navigation')
            </div>
            {{ $slot }}
        </div>
    </body>

    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>


</html>
