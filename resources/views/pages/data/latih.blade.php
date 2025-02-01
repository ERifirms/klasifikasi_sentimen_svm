<x-app-layout>
    <div class="py-2">
        <div class="w-full mx-auto sm:px-6 lg:px-2">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __('This is the Data Latih page.') }}

                    <div class="py-2">
                        <div class="max-w-full sm:px-6 lg:px-8">
                            {{-- Form upload CSV --}}
                            <div class="flex justify-between">
                                <div class="">
                                    <form action="{{ route('data-latih.upload') }}" method="POST"
                                        enctype="multipart/form-data" class="mt-4 inline-flex gap-4">
                                        @csrf
                                        <input type="file" name="file" id="xlsx_file" class="border p-2 rounded"
                                            required>
                                        @error('xlsx_file')
                                            <small class="text-red-500">{{ $message }}</small>
                                        @enderror
                                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Upload
                                            File</button>
                                    </form>
                                    <button onclick="openModal()"
                                        class="bg-green-500 text-white px-4 py-2 rounded mt-4 inline-flex items-center">
                                        Tambah Data
                                    </button>
                                </div>
                                <div class="">
                                    <form action="{{ route('prepocessing-tfidf.preprocessAndTfidf') }}" method="POST"
                                        enctype="multipart/form-data" class="mt-4 inline-flex gap-4">
                                        @csrf
                                        <button type="submit"
                                            class="bg-blue-500 text-white px-4 py-2 rounded">Prepocessing &
                                            TFIDF</button>
                                    </form>
                                </div>
                            </div>

                            {{-- Tombol Tambah Data --}}
                            <div id="addDataModal"
                                class="fixed inset-0 bg-gray-500 bg-opacity-50 hidden justify-center items-center z-50">
                                <div
                                    class="bg-white dark:bg-gray-700 rounded-lg shadow-lg p-6 w-1/3 transform -translate-x-1/2 -translate-y-1/2 absolute top-1/2 left-1/2">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Tambah Data
                                        Latih</h3>
                                    <form action="{{ route('addlatih-data.store') }}" method="POST">
                                        @csrf
                                        <div class="mb-4">
                                            <label for="content"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Content</label>
                                            <textarea id="content" name="content" class="w-full p-2 border rounded  dark:text-gray-600" required>{{ old('content') }}</textarea>
                                        </div>
                                        <div class="mb-4">
                                            <label for="sentimen"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 ">Sentiment</label>
                                            <select id="sentimen" name="sentimen"
                                                class="w-full p-2 border rounded  dark:text-gray-600" required>
                                                <option value="positif"
                                                    {{ old('sentimen') == 'positif' ? 'selected' : '' }}>Positif
                                                </option>
                                                <option value="negatif"
                                                    {{ old('sentimen') == 'negatif' ? 'selected' : '' }}>Negatif
                                                </option>
                                                <option value="netral"
                                                    {{ old('sentimen') == 'netral' ? 'selected' : '' }}>Netral</option>
                                            </select>
                                        </div>
                                        <div class="flex justify-end gap-4">
                                            <button type="button" onclick="closeModal()"
                                                class="bg-gray-500 text-white px-4 py-2 rounded">Batal</button>
                                            <button type="submit"
                                                class="bg-blue-500 text-white px-4 py-2 rounded">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            {{-- Tabel Data Latih --}}
                            <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-4 ">
                                <table
                                    class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 dark:border-spacing-1">
                                    <thead>
                                        <tr>
                                            <th scope="col"
                                                class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-gray-300">
                                                Content</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-gray-300">
                                                Sentiment</th>
                                            <th scope="col"
                                                class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase dark:text-gray-300">
                                                Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dataLatih as $data)
                                            <tr
                                                class="odd:bg-white even:bg-gray-100 dark:odd:bg-gray-800 dark:even:bg-gray-700">
                                                <td
                                                    class="px-6 py-4 whitespace-normal text-sm font-medium text-gray-800 dark:text-gray-100 break-words">
                                                    {{ $data->content }}
                                                </td>
                                                <td
                                                    class="px-6 py-4 whitespace-normal text-sm text-gray-800 dark:text-gray-100 break-words">
                                                    {{ $data->sentimen }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                                    <!-- Tombol Edit yang membuka modal -->
                                                    <a href="javascript:void(0)"
                                                        onclick="openEditModal({{ $data->id }}, '{{ $data->content }}', '{{ $data->sentimen }}')"
                                                        class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">Edit</a>

                                                    <!-- Tombol Delete -->
                                                    <form action="{{ route('data_latih.destroy', $data->id) }}"
                                                        method="POST" style="display:inline;"
                                                        onsubmit="return confirm('Are you sure you want to delete this data?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-red-600 hover:text-red-800 focus:outline-none focus:text-red-800 dark:text-red-500 dark:hover:text-red-400">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- Pagination --}}
                            <div class="mt-4">
                                {{ $dataLatih->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <-- Modal Edit Data --> --}}
    <div id="editDataModal" class="fixed inset-0 bg-gray-500 bg-opacity-50 hidden justify-center items-center z-50">
        <div
            class="bg-white dark:bg-gray-700 rounded-lg shadow-lg p-6 w-1/3 transform -translate-x-1/2 -translate-y-1/2 absolute top-1/2 left-1/2">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Edit Data Latih</h3>
            <form action="{{ route('updatelatih-data') }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" id="editDataId" name="id">
                <div class="mb-4">
                    <label for="editContent"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Content</label>
                    <textarea id="editContent" name="content" class="w-full p-2 border rounded   dark:text-gray-600" required></textarea>
                </div>
                <div class="mb-4">
                    <label for="editSentiment"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sentiment</label>
                    <select id="editSentiment" name="sentiment" class="w-full p-2 border rounded   dark:text-gray-600"
                        required>
                        <option value="positif">Positif</option>
                        <option value="negatif">Negatif</option>
                        <option value="netral">Netral</option>
                    </select>
                </div>
                <div class="flex justify-end gap-4">
                    <button type="button" onclick="closeEditModal()"
                        class="bg-gray-500 text-white px-4 py-2 rounded">Batal</button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Function to open modal with data
        function openModal() {
            document.getElementById("addDataModal").classList.remove("hidden");
        }

        // Fungsi untuk menutup modal
        function closeModal() {
            document.getElementById("addDataModal").classList.add("hidden");
        }

        function openEditModal(id, content, sentiment) {
            document.getElementById("editDataModal").classList.remove("hidden");
            document.getElementById("editDataId").value = id;
            document.getElementById("editContent").value = content;
            document.getElementById("editSentiment").value = sentiment;
        }

        // Fungsi untuk menutup modal
        function closeEditModal() {
            document.getElementById("editDataModal").classList.add("hidden");
        }
    </script>
</x-app-layout>
