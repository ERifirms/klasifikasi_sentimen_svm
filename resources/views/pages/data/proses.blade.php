<x-app-layout>
    <div class="py-2">
        <div class="w-full mx-auto sm:px-6 lg:px-4">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Hasil Preprocessing</h3>

                    <!-- Data Latih -->
                    <div class="mt-4">
                        <h4 class="font-bold mb-2">Data Latih</h4>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                        Content (Mentah)
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase">
                                        Hasil Preprocessing
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($preprocessedData as $item)
                                    <tr class="odd:bg-white even:bg-gray-100">
                                        <!-- Menampilkan Content Mentah -->
                                        <td class="px-6 py-4 text-sm font-medium text-gray-800">
                                            {{ $item['original_content'] }}
                                        </td>

                                        <!-- Menampilkan Hasil Preprocessing -->
                                        <td class="px-6 py-4 text-sm font-medium text-gray-800">
                                            {{ $item['preprocessed_content'] }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
