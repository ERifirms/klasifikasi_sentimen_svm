<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Klasifikasi') }}
        </h2>
    </x-slot>

    <div class="py-2">
        <div class="w-full mx-auto sm:px-6 lg:px-2">
            <!-- Div utama dengan tinggi layar penuh -->
            <div class="bg-white min-h-screen dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 h-full text-gray-900 dark:text-gray-100 flex flex-wrap gap-6">
                    <!-- Card di sebelah kiri -->
                    <div
                        class="w-full h-full sm:w-1/3 max-w-xs bg-white dark:bg-gray-700 rounded-lg shadow-lg overflow-hidden transform transition duration-300 hover:scale-105 hover:shadow-2xl">
                        <!-- Gambar card -->
                        <img src="{{ asset('images/svm.jpg') }}" alt="Gambar SVM" class="w-full h-64 object-cover">

                        <div class="p-6">
                            <!-- Judul atau deskripsi singkat -->
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Klasifikasi SVM</h3>
                            <!-- Form dengan tombol -->
                            <form action="{{ route('klasifikasi_svm') }}" method="POST" enctype="multipart/form-data"
                                class="mt-4">
                                @csrf
                                <button type="submit"
                                    class="w-full bg-blue-500 text-white px-6 py-3 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 transition duration-300">
                                    Klasifikasi SVM
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Area lainnya bisa ditambahkan di sini -->
                    <div class="flex-1 ml-6">
                        <!-- Judul Section -->
                        <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-6">
                            Klasifikasi Sentimen Analisis dengan Metode SVM
                        </h2>

                        <!-- Card Informasi Metode SVM -->
                        <div class="bg-white dark:bg-gray-700 rounded-lg shadow-lg p-6 mb-6">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Apa itu SVM?</h3>
                            <p class="text-gray-600 dark:text-gray-300 mb-4">
                                <strong>Support Vector Machine (SVM)</strong> adalah algoritma machine learning yang
                                digunakan untuk klasifikasi dan regresi. Dalam analisis sentimen, SVM dapat memisahkan
                                data teks menjadi dua atau lebih kelas sentimen (misalnya, positif, netral, negatif)
                                dengan menemukan hyperplane optimal.
                            </p>
                            <ul class="list-disc list-inside text-gray-600 dark:text-gray-300 mb-4">
                                <li><strong>Hyperplane:</strong> Garis pemisah optimal antara kelas sentimen.</li>
                                <li><strong>Kernel:</strong> Fungsi yang digunakan untuk memetakan data ke ruang dimensi
                                    tinggi.</li>
                                <li><strong>Akurasi Tinggi:</strong> SVM dikenal memiliki akurasi yang baik untuk
                                    dataset kecil hingga menengah.</li>
                            </ul>
                        </div>

                        <!-- Card Proses Klasifikasi -->
                        <div class="bg-white dark:bg-gray-700 rounded-lg shadow-lg p-6 mb-6">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Proses Klasifikasi
                                Sentimen</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <!-- Langkah 1: Preprocessing -->
                                <div class="bg-gray-50 dark:bg-gray-600 p-4 rounded-lg">
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">1.
                                        Preprocessing</h4>
                                    <p class="text-gray-600 dark:text-gray-300">
                                        Teks dibersihkan dengan menghapus stopwords, tokenisasi, dan stemming.
                                    </p>
                                </div>
                                <!-- Langkah 2: TF-IDF -->
                                <div class="bg-gray-50 dark:bg-gray-600 p-4 rounded-lg">
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">2. TF-IDF
                                    </h4>
                                    <p class="text-gray-600 dark:text-gray-300">
                                        Teks diubah menjadi vektor numerik menggunakan TF-IDF.
                                    </p>
                                </div>
                                <!-- Langkah 3: Pelatihan Model -->
                                <div class="bg-gray-50 dark:bg-gray-600 p-4 rounded-lg">
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">3. Pelatihan
                                        Model</h4>
                                    <p class="text-gray-600 dark:text-gray-300">
                                        Model SVM dilatih menggunakan data latih yang sudah diproses.
                                    </p>
                                </div>
                                <!-- Langkah 4: Prediksi -->
                                <div class="bg-gray-50 dark:bg-gray-600 p-4 rounded-lg">
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">4. Prediksi
                                    </h4>
                                    <p class="text-gray-600 dark:text-gray-300">
                                        Model digunakan untuk memprediksi sentimen dari data uji.
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>
