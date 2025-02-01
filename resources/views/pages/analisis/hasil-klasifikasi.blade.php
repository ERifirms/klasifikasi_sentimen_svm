<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-3xl font-semibold mb-6 text-center text-indigo-600">Hasil Analisis Klasifikasi SVM
                    </h1>

                    <!-- Akurasi -->
                    <div class="mb-6 bg-indigo-50 dark:bg-indigo-900 rounded-lg p-6 shadow-md">
                        <h2 class="text-xl font-semibold text-indigo-600 dark:text-indigo-400">Akurasi Model</h2>
                        <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $accuracy }}%</p>
                    </div>

                    <!-- Precision, Recall, dan F1-Score -->
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold mb-4 text-indigo-600 dark:text-indigo-400">Precision, Recall,
                            dan F1-Score</h2>
                        <div class="overflow-x-auto bg-gray-100 dark:bg-gray-700 rounded-lg shadow-md p-4">
                            <table
                                class="table-auto w-full border-collapse border border-gray-300 dark:border-gray-600">
                                <thead class="bg-indigo-100 dark:bg-indigo-600">
                                    <tr>
                                        <th class="px-4 py-2 border border-gray-300 dark:border-gray-600">Kelas</th>
                                        <th class="px-4 py-2 border border-gray-300 dark:border-gray-600">Precision</th>
                                        <th class="px-4 py-2 border border-gray-300 dark:border-gray-600">Recall</th>
                                        <th class="px-4 py-2 border border-gray-300 dark:border-gray-600">F1-Score</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($precision as $class => $value)
                                        <tr class="hover:bg-indigo-50 dark:hover:bg-indigo-800">
                                            <td
                                                class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-center">
                                                {{ $class }}</td>
                                            <td
                                                class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-center">
                                                {{ number_format($value * 100, 2) }}%</td>
                                            <td
                                                class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-center">
                                                {{ number_format($recall[$class] * 100, 2) }}%</td>
                                            <td
                                                class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-center">
                                                {{ number_format($f1Score[$class] * 100, 2) }}%</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Visualisasi hasil dengan grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Precision -->
                        <div class="bg-blue-50 dark:bg-blue-900 rounded-lg p-6 shadow-md">
                            <h3 class="text-xl font-semibold text-blue-600 dark:text-blue-400">Precision</h3>
                            @foreach ($precision as $class => $value)
                                <p class="text-lg text-gray-800 dark:text-gray-100">Kelas {{ $class }}:
                                    {{ number_format($value * 100, 2) }}%</p>
                            @endforeach
                        </div>

                        <!-- Recall -->
                        <div class="bg-yellow-50 dark:bg-yellow-900 rounded-lg p-6 shadow-md">
                            <h3 class="text-xl font-semibold text-yellow-600 dark:text-yellow-400">Recall</h3>
                            @foreach ($recall as $class => $value)
                                <p class="text-lg text-gray-800 dark:text-gray-100">Kelas {{ $class }}:
                                    {{ number_format($value * 100, 2) }}%</p>
                            @endforeach
                        </div>
                    </div>

                    <!-- F1-Score -->
                    <div class="mt-6">
                        <h2 class="text-xl font-semibold mb-4 text-indigo-600 dark:text-indigo-400">F1-Score</h2>
                        <div class="bg-green-50 dark:bg-green-900 rounded-lg p-6 shadow-md">
                            @foreach ($f1Score as $class => $value)
                                <p class="text-lg text-gray-800 dark:text-gray-100">Kelas {{ $class }}:
                                    {{ number_format($value * 100, 2) }}%</p>
                            @endforeach
                        </div>
                    </div>

                    <!-- Confusion Matrix -->
                    <div class="mt-6">
                        <h2 class="text-xl font-semibold mb-4 text-indigo-600 dark:text-indigo-400">Confusion Matrix
                        </h2>
                        <div class="bg-gray-100 dark:bg-gray-700 rounded-lg shadow-md p-6">
                            <table class="w-full border-collapse border border-gray-300 dark:border-gray-600">
                                <thead class="bg-indigo-100 dark:bg-indigo-600">
                                    <tr>
                                        <th class="px-4 py-2 border border-gray-300 dark:border-gray-600">Prediksi
                                            Positif</th>
                                        <th class="px-4 py-2 border border-gray-300 dark:border-gray-600">Prediksi
                                            Negatif</th>
                                        <th class="px-4 py-2 border border-gray-300 dark:border-gray-600">Prediksi
                                            Netral</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-center">
                                            {{ $confusionMatrix['positif']['TP'] }}
                                        </td>
                                        <td class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-center">
                                            {{ $confusionMatrix['positif']['FP'] }}
                                        </td>
                                        <td class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-center">
                                            {{ $confusionMatrix['netral']['FN'] }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-center">
                                            {{ $confusionMatrix['negatif']['TP'] }}
                                        </td>
                                        <td class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-center">
                                            {{ $confusionMatrix['negatif']['FP'] }}
                                        </td>
                                        <td class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-center">
                                            {{ $confusionMatrix['negatif']['FN'] }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tabel untuk Merged Data -->
                    <table class=" mt-5 w-full border border-gray-300 shadow-lg rounded-lg overflow-hidden">
                        <thead class="bg-blue-600 text-white">
                            <tr>
                                <th class="px-4 py-2">No</th>
                                <th class="px-4 py-2">Content</th>
                                <th class="px-4 py-2">Sentimen Manual</th>
                                <th class="px-4 py-2">Sentimen Mesin</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-800">
                            @foreach ($pagination as $index => $content)
                                <tr class="odd:bg-gray-100 even:bg-white">
                                    <td class="px-4 py-2 text-center">
                                        {{ $loop->iteration + ($pagination->currentPage() - 1) * $pagination->perPage() }}
                                    </td>
                                    <td class="px-4 py-2">{{ is_array($content) ? json_encode($content) : $content }}
                                    </td>
                                    <td class="px-4 py-2 text-center">{{ $pagedLabels[$index] ?? 'N/A' }}</td>
                                    <td class="px-4 py-2 text-center">{{ $newPredictions[$index] ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Pagination Links -->
                    <div class="mt-4 flex justify-end">
                        {{ $pagination->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
