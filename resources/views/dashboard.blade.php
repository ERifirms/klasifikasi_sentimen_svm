<x-app-layout>
    <div class="p-6 flex gap-6">
        <!-- Main Content (75%) -->
        <div class="w-[75%] space-y-6">
            <!-- Welcome Card -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 rounded-lg shadow-lg text-white dark:bg-gray-800">
                <p class="text-2xl font-bold mb-2">Selamat Datang di Aplikasi SVM</p>
                <p class="text-sm">Aplikasi Klasifikasi Sentimen Analisis Menggunakan Metode Support Vector Machine</p>
            </div>

            <!-- Data Overview Cards -->
            <div class="grid grid-cols-3 gap-6">
                <!-- Data Latih Card -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md flex items-center space-x-4">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-600 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-500 dark:text-white" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 dark:text-gray-300">Data Latih</p>
                        <p class="text-xl font-bold text-gray-800 dark:text-white">{{ $jumlahLatih }}</p>
                    </div>
                </div>

                <!-- Data Uji Card -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md flex items-center space-x-4">
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-600 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-500 dark:text-white" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 dark:text-gray-300">Data Uji</p>
                        <p class="text-xl font-bold text-gray-800 dark:text-white">{{ $jumlahUji }}</p>
                    </div>
                </div>

                <!-- Accuracy Card -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md flex items-center space-x-4">
                    <div
                        class="w-12 h-12 bg-purple-100 dark:bg-purple-600 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-500 dark:text-white" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-600 dark:text-gray-300">Dataset</p>
                        <p class="text-xl font-bold text-gray-800 dark:text-white">{{ $jumlahLatih + $jumlahUji }}</p>
                    </div>
                </div>
            </div>

            <!-- Grafik Hasil Analisis -->
            <!-- Grafik Data Latih dan Data Uji -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                <p class="text-xl font-bold text-gray-800 dark:text-white mb-4">Grafik Data Latih vs Data Uji</p>
                <div class="h-96">
                    <canvas id="dataChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Sidebar (25%) -->
        <div class="w-[25%] space-y-6">
            <!-- Info Data Card -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                <p class="text-xl font-bold text-gray-800 dark:text-white mb-6">Info Data</p>
                <div class="space-y-6">
                    <!-- Data Latih -->
                    <div class="flex items-center space-x-4">
                        <div
                            class="w-12 h-12 bg-blue-100 dark:bg-blue-600 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-500 dark:text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-600 dark:text-gray-300">Data Latih</p>
                            <p class="text-sm font-bold text-gray-800 dark:text-white">{{ $jumlahLatih }}</p>
                        </div>
                    </div>

                    <!-- Data Uji -->
                    <div class="flex items-center space-x-4">
                        <div
                            class="w-12 h-12 bg-green-100 dark:bg-green-600 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-500 dark:text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-gray-600 dark:text-gray-300">Data Uji</p>
                            <p class="text-sm font-bold text-gray-800 dark:text-white">{{ $jumlahUji }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            {{-- <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                <p class="text-xl font-bold text-gray-800 dark:text-white mb-6">Quick Actions</p>
                <div class="space-y-4">
                    <a href="{{ route('data_latih.create') }}"
                        class="block w-full text-center bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition-colors">Tambah
                        Data Latih</a>
                    <a href="{{ route('data_uji.create') }}"
                        class="block w-full text-center bg-green-500 text-white py-2 rounded-lg hover:bg-green-600 transition-colors">Tambah
                        Data Uji</a>
                    <a href="{{ route('klasifikasi') }}"
                        class="block w-full text-center bg-purple-500 text-white py-2 rounded-lg hover:bg-purple-600 transition-colors">Mulai
                        Klasifikasi</a>
                </div>
            </div> --}}
        </div>
    </div>

    <!-- Script untuk Chart.js -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('dataChart').getContext('2d');
            const dataChart = new Chart(ctx, {
                type: 'bar', // Jenis grafik (bisa diganti ke 'line', 'pie', dll.)
                data: {
                    labels: ['Data Latih', 'Data Uji'], // Label sumbu X
                    datasets: [{
                        label: 'Jumlah Data',
                        data: [{{ $jumlahLatih }}, {{ $jumlahUji }}], // Data dari controller
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.8)', // Warna untuk Data Latih
                            'rgba(75, 192, 192, 0.8)' // Warna untuk Data Uji
                        ],
                        borderColor: [
                            'rgba(54, 162, 235, 1)',
                            'rgba(75, 192, 192, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        },
                        tooltip: {
                            enabled: true
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
