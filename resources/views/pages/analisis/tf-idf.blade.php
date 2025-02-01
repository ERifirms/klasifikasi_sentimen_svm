<x-app-layout>
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Hasil Perhitungan TF-IDF</h3>

                    <!-- Menampilkan hasil TF-IDF -->
                    <h4 class="font-semibold mt-8 mb-2">Skor TF-IDF</h4>
                    @if (isset($tfidfScores) && !empty($tfidfScores))
                        <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-left border">
                                        Term</th>
                                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-left border">
                                        Skor TF-IDF</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tfidfScores as $term => $score)
                                    <tr class="odd:bg-white even:bg-gray-100">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-800 border">
                                            {{ $term }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-800 border">
                                            {{ number_format($score, 4) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-red-500">Tidak ada skor TF-IDF yang tersedia.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
