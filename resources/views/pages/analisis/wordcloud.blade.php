<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Word Cloud') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Container untuk Word Cloud -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div id="word-cloud"
                    class="w-full h-[600px] sm:h-[700px] p-4 bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                    <!-- Word cloud akan muncul di sini -->
                </div>
            </div>

            <script>
                window.onload = function() {
                    // Ambil data kata dan frekuensinya dari server (Laravel backend)
                    const wordData = @json($wordData);

                    // Konfigurasi Word Cloud
                    WordCloud(document.getElementById('word-cloud'), {
                        list: wordData,
                        gridSize: 20, // Ukuran grid untuk mengatur jarak antar kata
                        weightFactor: 20, // Faktor skala untuk ukuran kata
                        fontFamily: 'Arial, sans-serif', // Font yang digunakan
                        color: 'random-dark', // Warna kata (acak gelap)
                        backgroundColor: '#f3f4f6', // Warna latar belakang (grey-100)
                        rotateRatio: 0.5, // Rasio rotasi kata (50% kata akan dirotasi)
                        rotationSteps: 4, // Jumlah langkah rotasi
                        minSize: 10, // Ukuran minimum kata
                        hover: (item, dimension, event) => {
                            // Tooltip untuk menampilkan frekuensi kata saat dihover
                            const tooltip = document.createElement('div');
                            tooltip.className = 'word-cloud-tooltip';
                            tooltip.innerHTML = `<strong>${item[0]}</strong>: ${item[1]} occurrences`;
                            tooltip.style.position = 'absolute';
                            tooltip.style.left = `${event.clientX + 10}px`;
                            tooltip.style.top = `${event.clientY + 10}px`;
                            tooltip.style.backgroundColor = 'rgba(0, 0, 0, 0.8)';
                            tooltip.style.color = '#fff';
                            tooltip.style.padding = '5px 10px';
                            tooltip.style.borderRadius = '4px';
                            tooltip.style.fontSize = '14px';
                            tooltip.style.zIndex = '1000';
                            document.body.appendChild(tooltip);

                            // Hapus tooltip saat mouse meninggalkan kata
                            document.getElementById('word-cloud').addEventListener('mouseleave', () => {
                                tooltip.remove();
                            });
                        },
                        click: (item) => {
                            // Aksi saat kata diklik (opsional)
                            alert(`Kata "${item[0]}" muncul sebanyak ${item[1]} kali.`);
                        }
                    });
                };
            </script>
        </div>
    </div>

    <!-- Style untuk tooltip -->
    <style>
        .word-cloud-tooltip {
            pointer-events: none;
            /* Mencegah tooltip mengganggu interaksi */
        }
    </style>
</x-app-layout>
