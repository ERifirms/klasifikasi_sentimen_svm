<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataLatih;
use Illuminate\Support\Facades\Log;

class PreprocessingController extends Controller
{
    // Fungsi untuk preprocessing dan TF-IDF
    public function preprocessAndTfidf(Request $request)
    {
        // Ambil data latih dari database
        $dataLatih = DataLatih::all(); // Ambil data lengkap (termasuk kolom content)

        // Cek apakah dataLatih ada dan tidak kosong
        if ($dataLatih->isEmpty()) {
            Log::error('Data latih kosong');
            return redirect()->back()->with('error', 'Data latih kosong.');
        }

        // 1. Preprocessing Data Latih
        $preprocessedData = $dataLatih->map(function ($item) {
            return [
                'id' => $item->id,
                'original_content' => $item->content,
                'preprocessed_content' => $this->preprocessText($item->content), // Proses teks
                'sentimen' => $item->sentimen
            ];
        })->toArray();

        // Filter data valid
        $filteredData = array_values(array_filter($preprocessedData, function ($item) {
            return is_array($item) && isset($item['preprocessed_content']) && !empty($item['preprocessed_content']);
        }));

        if (empty($filteredData)) {
            Log::error('Tidak ada data valid untuk dihitung TF-IDF.');
            return redirect()->back()->with('error', 'Data latih kosong setelah filtering.');
        }

        // Ambil konten dokumen
        $documents = array_column($filteredData, 'preprocessed_content');

        if (empty($documents)) {
            Log::error('Tidak ada dokumen untuk dihitung TF-IDF.');
            return redirect()->back()->with('error', 'Dokumen kosong setelah filtering.');
        }

        // Fungsi untuk menghitung TF
        function calculateTF($term, $document)
        {
            $words = explode(" ", $document);
            $termCount = count(array_filter($words, fn($word) => $word === $term));
            return $termCount / count($words);
        }

        // Fungsi untuk menghitung IDF
        function calculateIDF($term, $documents)
        {
            $numDocs = count($documents);
            $docsWithTerm = count(array_filter($documents, fn($doc) => str_contains($doc, $term)));
            return log($numDocs / ($docsWithTerm ?: 1)); // Hindari pembagian dengan nol
        }

        // Fungsi untuk menghitung TF-IDF
        function calculateTFIDF($term, $document, $documents)
        {
            $tf = calculateTF($term, $document);
            $idf = calculateIDF($term, $documents);
            return $tf * $idf;
        }

        // Proses semua dokumen untuk menghitung TF-IDF
        $tfidfScores = [];
        foreach ($documents as $document) {
            $words = explode(" ", $document);
            foreach ($words as $term) {
                if (!isset($tfidfScores[$term])) {
                    $tfidfScores[$term] = 0;
                }
                $tfidfScores[$term] += calculateTFIDF($term, $document, $documents);
            }
        }
        session(['tfidf_scores' => $tfidfScores]);
        session(['hasil_prepocessing' => $preprocessedData]);

        // Kirim data ke view
        return view('pages.data.proses', compact('preprocessedData',));
    }
    // Fungsi untuk preprocessing teks
    private function preprocessText($text)
    {
        // Ubah teks menjadi huruf kecil
        $text = strtolower($text);

        // Hilangkan angka, tanda baca, dan karakter khusus
        $text = preg_replace('/[0-9]/', '', $text);
        $text = preg_replace('/[^\p{L}\s]/u', '', $text);

        // Hilangkan spasi berlebih
        $text = preg_replace('/\s+/', ' ', $text);

        // Hapus stopwords (contoh stopwords)
        $stopwords = [
            'ada',
            'adalah',
            'adanya',
            'adapun',
            'agak',
            'agaknya',
            'agar',
            'akan',
            'akankah',
            'akhir',
            'akhiri',
            'akhirnya',
            'aku',
            'akulah',
            'amat',
            'amatlah',
            'anda',
            'andalah',
            'antar',
            'antara',
            'antaranya',
            'apa',
            'apaan',
            'apabila',
            'apakah',
            'apalagi',
            'apatah',
            'artinya',
            'asal',
            'asalkan',
            'atas',
            'atau',
            'ataukah',
            'ataupun',
            'awal',
            'awalnya',
            'bagai',
            'bagaikan',
            'bagaimana',
            'bagaimanakah',
            'bagaimanapun',
            'bagi',
            'bagian',
            'bahkan',
            'bahwa',
            'bahwasanya',
            'baik',
            'bakal',
            'bakalan',
            'balik',
            'banyak',
            'bapak',
            'baru',
            'bawah',
            'beberapa',
            'begini',
            'beginian',
            'beginikah',
            'beginilah',
            'begitu',
            'begitukah',
            'begitulah',
            'begitupun',
            'bekerja',
            'belakang',
            'belakangan',
            'belum',
            'belumlah',
            'benar',
            'benarkah',
            'benarlah',
            'berada',
            'berakhir',
            'berakhirlah',
            'berakhirnya',
            'berapa',
            'berapakah',
            'berapalah',
            'berapapun',
            'berarti',
            'berawal',
            'berbagai',
            'berdatangan',
            'beri',
            'berikan',
            'berikut',
            'berikutnya',
            'berjumlah',
            'berkali-kali',
            'berkata',
            'berkehendak',
            'berkeinginan',
            'berkenaan',
            'berlainan',
            'berlalu',
            'berlangsung',
            'berlebihan',
            'bermacam',
            'bermacam-macam',
            'bermaksud',
            'bermula',
            'bersama',
            'bersama-sama',
            'bersiap',
            'bersiap-siap',
            'bertanya',
            'bertanya-tanya',
            'berturut',
            'berturut-turut',
            'bertutur',
            'berujar',
            'berupa',
            'besar',
            'betul',
            'betulkah',
            'biasa',
            'biasanya',
            'bila',
            'bilakah',
            'bisa',
            'bisakah',
            'boleh',
            'bolehkah',
            'bolehlah',
            'buat',
            'bukan',
            'bukankah',
            'bukanlah',
            'bukannya',
            'bulan',
            'bung',
            'cara',
            'caranya',
            'cukup',
            'cukupkah',
            'cukuplah',
            'cuma',
            'dahulu',
            'dalam',
            'dan',
            'dapat',
            'dari',
            'daripada',
            'datang',
            'dekat',
            'demi',
            'demikian',
            'demikianlah',
            'dengan',
            'depan',
            'di',
            'dia',
            'diakhiri',
            'diakhirinya',
            'dialah',
            'diantara',
            'diantaranya',
            'diberi',
            'diberikan',
            'diberikannya',
            'dibuat',
            'dibuatnya',
            'didapat',
            'didatangkan',
            'digunakan',
            'diibaratkan',
            'diibaratkannya',
            'diingat',
            'diingatkan',
            'diinginkan',
            'dijawab',
            'dijelaskan',
            'dijelaskannya',
            'dikarenakan',
            'dikatakan',
            'dikatakannya',
            'dikerjakan',
            'diketahui',
            'diketahuinya',
            'dikira',
            'dilakukan',
            'dilalui',
            'dilihat',
            'dimaksud',
            'dimaksudkan',
            'dimaksudkannya',
            'dimaksudnya',
            'diminta',
            'dimintai',
            'dimisalkan',
            'dimulai',
            'dimulailah',
            'dimulainya',
            'dimungkinkan',
            'dini',
            'dipastikan',
            'diperbuat',
            'diperbuatnya',
            'dipergunakan',
            'diperkirakan',
            'diperlihatkan',
            'diperlukan',
            'diperlukannya',
            'dipersoalkan',
            'dipertanyakan',
            'dipunyai',
            'diri',
            'dirinya',
            'disampaikan',
            'disebut',
            'disebutkan',
            'disebutkannya',
            'disini',
            'disinilah',
            'ditambahkan',
            'ditandaskan',
            'ditanya',
            'ditanyai',
            'ditanyakan',
            'ditegaskan',
            'ditujukan',
            'ditunjuk',
            'ditunjuki',
            'ditunjukkan',
            'ditunjukkannya',
            'ditunjuknya',
            'dituturkan',
            'dituturkannya',
            'diucapkan',
            'diucapkannya',
            'diungkapkan',
            'dong',
            'dua',
            'dulu',
            'empat',
            'enggak',
            'enggaknya',
            'entah',
            'entahlah',
            'guna',
            'gunakan',
            'hal',
            'hampir',
            'hanya',
            'hanyalah',
            'hari',
            'harus',
            'haruslah',
            'harusnya',
            'hendak',
            'hendaklah',
            'hendaknya',
            'hingga',
            'ia',
            'ialah',
            'ibarat',
            'ibaratkan',
            'ibaratnya',
            'ibu',
            'ikut',
            'ingat',
            'ingat-ingat',
            'ingin',
            'inginkah',
            'inginkan',
            'ini',
            'inikah',
            'inilah',
            'itu',
            'itukah',
            'itulah',
            'jadi',
            'jadilah',
            'jadinya',
            'jangan',
            'jangankan',
            'janganlah',
            'jauh',
            'jawab',
            'jawaban',
            'jawabnya',
            'jelas',
            'jelaskan',
            'jelaslah',
            'jelasnya',
            'jika',
            'jikalau',
            'juga',
            'jumlah',
            'jumlahnya',
            'justru',
            'kala',
            'kalau',
            'kalaulah',
            'kalaupun',
            'kalian',
            'kami',
            'kamilah',
            'kamu',
            'kamulah',
            'kan',
            'kapan',
            'kapankah',
            'kapanpun',
            'karena',
            'karenanya',
            'kasus',
            'kata',
            'katakan',
            'katakanlah',
            'katanya',
            'ke',
            'keadaan',
            'kebetulan',
            'kecil',
            'kedua',
            'keduanya',
            'keinginan',
            'kelamaan',
            'kelihatan',
            'kelihatannya',
            'kelima',
            'keluar',
            'kembali',
            'kemudian',
            'kemungkinan',
            'kemungkinannya',
            'kenapa',
            'kepada',
            'kepadanya',
            'kesampaian',
            'keseluruhan',
            'keseluruhannya',
            'keterlaluan',
            'ketika',
            'khususnya',
            'kini',
            'kinilah',
            'kira',
            'kira-kira',
            'kiranya',
            'kita',
            'kitalah',
            'kok',
            'kurang',
            'lagi',
            'lagian',
            'lah',
            'lain',
            'lainnya',
            'lalu',
            'lama',
            'lamanya',
            'lanjut',
            'lanjutnya',
            'lebih',
            'lewat',
            'lima',
            'luar',
            'macam',
            'maka',
            'makanya',
            'makin',
            'malah',
            'malahan',
            'mampu',
            'mampukah',
            'mana',
            'manakala',
            'manalagi',
            'masa',
            'masalah',
            'masalahnya',
            'masih',
            'masihkah',
            'masing',
            'masing-masing',
            'mau',
            'maupun',
            'melainkan',
            'melakukan',
            'melalui',
            'melihat',
            'melihatnya',
            'memang',
            'memastikan',
            'memberi',
            'memberikan',
            'membuat',
            'memerlukan',
            'memihak',
            'meminta',
            'memintakan',
            'memisalkan',
            'memperbuat',
            'mempergunakan',
            'memperkirakan',
            'memperlihatkan',
            'mempersiapkan',
            'mempersoalkan',
            'mempertanyakan',
            'mempunyai',
            'memulai',
            'memungkinkan',
            'menaiki',
            'menambahkan',
            'menandaskan',
            'menanti',
            'menanti-nanti',
            'menantikan',
            'menanya',
            'menanyai',
            'menanyakan',
            'mendapat',
            'mendapatkan',
            'mendatang',
            'mendatangi',
            'mendatangkan',
            'menegaskan',
            'mengakhiri',
            'mengapa',
            'mengatakan',
            'mengatakannya',
            'mengenai',
            'mengerjakan',
            'mengetahui',
            'menggunakan',
            'menghendaki',
            'mengibaratkan',
            'mengibaratkannya',
            'mengingat',
            'mengingatkan',
            'menginginkan',
            'mengira',
            'mengucapkan',
            'mengucapkannya',
            'mengungkapkan',
            'menjadi',
            'menjawab',
            'menjelaskan',
            'menuju',
            'menunjuk',
            'menunjuki',
            'menunjukkan',
            'menunjuknya',
            'menurut',
            'menuturkan',
            'menyampaikan',
            'menyangkut',
            'menyatakan',
            'menyebutkan',
            'menyeluruh',
            'menyiapkan',
            'merasa',
            'mereka',
            'merekalah',
            'merupakan',
            'meski',
            'meskipun',
            'meyakini',
            'meyakinkan',
            'minta',
            'mirip',
            'misal',
            'misalkan',
            'misalnya',
            'mula',
            'mulai',
            'mulailah',
            'mulanya',
            'mungkin',
            'mungkinkah',
            'nah',
            'naik',
            'namun',
            'nanti',
            'nantinya',
            'nyaris',
            'nyatanya',
            'oleh',
            'olehnya',
            'pada',
            'padahal',
            'padanya',
            'pak',
            'paling',
            'panjang',
            'pantas',
            'para',
            'pasti',
            'pastilah',
            'penting',
            'pentingnya',
            'per',
            'percuma',
            'perlu',
            'perlukah',
            'perlunya',
            'pernah',
            'persoalan',
            'pertama',
            'pertama-tama',
            'pertanyaan',
            'pertanyakan',
            'pihak',
            'pihaknya',
            'pukul',
            'pula',
            'pun',
            'punya',
            'rasa',
            'rasanya',
            'rata',
            'rupanya',
            'saat',
            'saatnya',
            'saja',
            'sajalah',
            'saling',
            'sama',
            'sama-sama',
            'sangat',
            'sangatlah',
            'satu',
            'saya',
            'sayalah',
            'se',
            'seakan',
            'seakan-akan',
            'sebab',
            'sebabnya',
            'sebuah',
            'sebut',
            'sebutkan',
            'sebutlah',
            'sebutnya',
            'sedang',
            'sedangkan',
            'sedikit',
            'sejak',
            'sejauh',
            'sejalan',
            'sejaknya',
            'sekadar',
            'sekali',
            'sekalian',
            'sekaligus',
            'sekarang',
            'sekaranglah',
            'sekalipun',
            'sekarangpun',
            'selain',
            'selalu',
            'selanjutnya',
            'seluruh',
            'seluruhnya',
            'sembari',
            'sementara',
            'semisal',
            'semisalnya',
            'semua',
            'semua-semua',
            'semuanya',
            'sendiri',
            'sendirinya',
            'seolah',
            'seolah-olah',
            'seorang',
            'seorangpun',
            'sering',
            'seringkali',
            'serta',
            'sesaat',
            'sesekali',
            'sesekian',
            'sesuai',
            'sesuailah',
            'sesungguhnya',
            'setelah',
            'setengah',
            'seterusnya',
            'setiap',
            'si',
            'siapa',
            'siapakah',
            'siapalah',
            'sibuk',
            'sudah',
            'sudahkah',
            'sudahlah',
            'sudahlah',
            'sumber',
            'suka',
            'sukanya',
            'tak',
            'tanya',
            'tanpa',
            'tanya-tanya',
            'tepat',
            'terakhir',
            'terbukti',
            'terhadap',
            'terjadi',
            'terjadilah',
            'terjadinya',
            'terjadi',
            'terlahir',
            'terlalu',
            'terlihat',
            'terlihatnya',
            'terlupakan',
            'termasuk',
            'ternyata',
            'terus',
            'teruslah',
            'terusnya',
            'tetap',
            'tetaplah',
            'tetapnya',
            'tidak',
            'tidakkah',
            'tidaklah',
            'tidakpun',
            'tiga',
            'tinggi',
            'tingginya',
            'tunjuk',
            'tunjukkan',
            'turut',
            'untuk',
            'untuklah',
            'yaitu'];
        $text = collect(explode(' ', $text))
            ->reject(fn($word) => in_array($word, $stopwords))
            ->implode(' ');

        return $text;
    }

    // Fungsi untuk menampilkan hasil preprocessing dan TF-IDF
    public function showHasilPreprocessing()
    {
        // Ambil data latih dari database
        $dataLatih = DataLatih::all();

        $preprocessedData = session('hasil_prepocessing', []);

        // Kirim data ke view
        return view('pages.data.proses', compact('dataLatih', 'preprocessedData'));
    }

    // public function hitungTFIDF()
    // {
    //     // Ambil data latih dari sesi
    //     $dataLatih = session('hasil_prepocessing', []);

    //     // Filter data valid
    //     $filteredData = array_values(array_filter($dataLatih, function ($item) {
    //         return is_array($item) && isset($item['preprocessed_content']) && !empty($item['preprocessed_content']);
    //     }));

    //     if (empty($filteredData)) {
    //         Log::error('Tidak ada data valid untuk dihitung TF-IDF.');
    //         return redirect()->back()->with('error', 'Data latih kosong setelah filtering.');
    //     }

    //     // Ambil konten dokumen
    //     $documents = array_column($filteredData, 'preprocessed_content');

    //     if (empty($documents)) {
    //         Log::error('Tidak ada dokumen untuk dihitung TF-IDF.');
    //         return redirect()->back()->with('error', 'Dokumen kosong setelah filtering.');
    //     }

    //     // Fungsi untuk menghitung TF
    //     function calculateTF($term, $document)
    //     {
    //         $words = explode(" ", $document);
    //         $termCount = count(array_filter($words, fn($word) => $word === $term));
    //         return $termCount / count($words);
    //     }

    //     // Fungsi untuk menghitung IDF
    //     function calculateIDF($term, $documents)
    //     {
    //         $numDocs = count($documents);
    //         $docsWithTerm = count(array_filter($documents, fn($doc) => str_contains($doc, $term)));
    //         return log($numDocs / ($docsWithTerm ?: 1)); // Hindari pembagian dengan nol
    //     }

    //     // Fungsi untuk menghitung TF-IDF
    //     function calculateTFIDF($term, $document, $documents)
    //     {
    //         $tf = calculateTF($term, $document);
    //         $idf = calculateIDF($term, $documents);
    //         return $tf * $idf;
    //     }

    //     // Proses semua dokumen untuk menghitung TF-IDF
    //     $tfidfScores = [];
    //     foreach ($documents as $document) {
    //         $words = explode(" ", $document);
    //         foreach ($words as $term) {
    //             if (!isset($tfidfScores[$term])) {
    //                 $tfidfScores[$term] = 0;
    //             }
    //             $tfidfScores[$term] += calculateTFIDF($term, $document, $documents);
    //         }
    //     }
    //     session(['tfidf_scores' => $tfidfScores]);
    //     return view('pages.analisis.tf-idf', compact('tfidfScores'));
    // }



    public function lihatTFIDF()
    {
        // Ambil skor TF-IDF dari sesi
        $tfidfScores = session('tfidf_scores', []);

        // Cek apakah skor TF-IDF tersedia
        if (empty($tfidfScores)) {
            return redirect()->back()->with('error', 'Skor TF-IDF tidak tersedia. Harap hitung TF-IDF terlebih dahulu.');
        }

        // Kirim skor TF-IDF ke view
        return view('pages.analisis.tf-idf', compact('tfidfScores'));
    }
}
