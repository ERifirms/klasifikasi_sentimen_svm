<?php

namespace App\Http\Controllers;

use App\Models\DataLatih;
use App\Models\DataUji;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Phpml\Classification\SVC;
use Phpml\SupportVectorMachine\Kernel;
use Phpml\Metric\Accuracy;
use Phpml\Metric\ConfusionMatrix;
use Phpml\FeatureExtraction\TokenCountVectorizer;
use Phpml\FeatureExtraction\TfIdfTransformer;
use Phpml\Tokenization\WhitespaceTokenizer;

// Menghapus semua data dalam tabel tertentu
// DB::table('data_')->delete();

class KlasifikasiSVM extends Controller
{
    public function index()
    {
        return view('klasifikasi');
    }

    public function klasifikasiSVM(Request $request)
    {
        // Ambil data latih dari sesi atau database
        $DataLatih = session('hasil_prepocessing', []);
        if (empty($DataLatih)) {
            return response()->json([
                'error' => 'Data latih tidak ditemukan. Pastikan preprocessing data telah dilakukan sebelumnya.'
            ], 400);
        }

        $trainSamples = array_column($DataLatih, 'preprocessed_content');
        $trainLabels = array_column($DataLatih, 'sentimen');

        if (empty($trainSamples) || empty($trainLabels)) {
            return response()->json([
                'error' => 'Data latih tidak memiliki konten atau label yang sesuai.'
            ], 400);
        }

        // Ambil data uji
        $dataUji = DataUji::all(['content', 'sentimen']);
        if ($dataUji->isEmpty()) {
            return response()->json([
                'error' => 'Data uji tidak ditemukan. Pastikan data uji tersedia di database.'
            ], 400);
        }

        // Persiapkan data uji
        $testSamples = $dataUji->pluck('content')->toArray();
        $testLabels = $dataUji->pluck('sentimen')->toArray();

        // Preprocessing: TF-IDF Vectorization
        $vectorizer = new TokenCountVectorizer(new WhitespaceTokenizer());
        $tfIdfTransformer = new TfIdfTransformer();

        $vectorizer->fit($trainSamples);
        $vectorizer->transform($trainSamples);
        $tfIdfTransformer->fit($trainSamples);
        $tfIdfTransformer->transform($trainSamples);

        $vectorizer->transform($testSamples);
        $tfIdfTransformer->transform($testSamples);

        // Inisialisasi classifier SVM
        $classifier = new SVC(Kernel::RBF, $cost = 10, $gamma = 0.1);

        try {
            // Train classifier
            $classifier->train($trainSamples, $trainLabels);
            $predictions = $classifier->predict($testSamples);

            // Hitung akurasi
            $accuracy = Accuracy::score($testLabels, $predictions) * 100;

            // Hitung Confusion Matrix
            $confusionMatrix = $this->computeConfusionMatrix($testLabels, $predictions);

            // Calculate Precision, Recall, and F1-Score
            $precision = [];
            $recall = [];
            $f1Score = [];
            foreach (['negatif', 'positif'] as $class) {
                $tp = $confusionMatrix[$class]['TP'] ?? 0;
                $fp = $confusionMatrix[$class]['FP'] ?? 0;
                $fn = $confusionMatrix[$class]['FN'] ?? 0;

                // Precision
                $precision[$class] = ($tp + $fp) > 0 ? $tp / ($tp + $fp) : 0;
                // Recall
                $recall[$class] = ($tp + $fn) > 0 ? $tp / ($tp + $fn) : 0;
                // F1-Score
                $f1Score[$class] = ($precision[$class] + $recall[$class]) > 0
                    ? 2 * ($precision[$class] * $recall[$class]) / ($precision[$class] + $recall[$class])
                    : 0;
            }

            // Combine new data for predictions
            $newdatabaru1 = DataLatih::all(['content', 'sentimen']);
            $newdatabaru2 = DataUji::all(['content', 'sentimen']);
            $dataujibaru1 = $newdatabaru1->pluck('content')->toArray();
            $datalabelbaru1 = $newdatabaru1->pluck('sentimen')->toArray();
            $dataujibaru2 = $newdatabaru2->pluck('content')->toArray();
            $datalabelbaru2 = $newdatabaru2->pluck('sentimen')->toArray();

            // Combine the new data
            $databaru = array_merge($dataujibaru1, $dataujibaru2);
            $labelbaru = array_merge($datalabelbaru1, $datalabelbaru2);

            // Pagination manually
            $page = request()->get('page', 1);
            $perPage = 10;
            $offset = ($page - 1) * $perPage;

            $pagedData = array_slice($databaru, $offset, $perPage);
            $pagedLabels = array_slice($labelbaru, $offset, $perPage);

            $pagination = new LengthAwarePaginator(
                $pagedData,
                count($databaru),
                $perPage,
                $page,
                ['path' => request()->url()]
            );

            // Transform new data for predictions
            $vectorizer->transform($databaru);
            $tfIdfTransformer->transform($databaru);

            $newPredictions = $classifier->predict($databaru);

            // Store data in session
            session([
                'pagination' => $pagination,
                'newPredictions' => $newPredictions,
                'accuracy' => $accuracy,
                'precision' => $precision,
                'recall' => $recall,
                'f1Score' => $f1Score,
                'confusionMatrix' => $confusionMatrix,
                'pagedLabels' => $pagedLabels,
                'predictions' => $predictions
            ]);

            // Return view with data
            return view('pages.analisis.hasil-klasifikasi', compact(
                'accuracy',
                'precision',
                'recall',
                'f1Score',
                'predictions',
                'pagination',
                'pagedLabels',
                'newPredictions',
                'confusionMatrix'
            ));
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan saat melakukan klasifikasi: ' . $e->getMessage()
            ], 500);
        }
    }

    private function computeConfusionMatrix($trueLabels, $predictedLabels)
    {
        // Inisialisasi confusion matrix dengan semua kelas yang diharapkan
        $confusionMatrix = [
            'negatif' => ['TP' => 0, 'FP' => 0, 'FN' => 0],
            'positif' => ['TP' => 0, 'FP' => 0, 'FN' => 0]
        ];

        foreach ($trueLabels as $index => $trueLabel) {
            $predictedLabel = $predictedLabels[$index];

            if (!isset($confusionMatrix[$trueLabel])) {
                // Jika kelas tidak ada di confusion matrix, tambahkan
                $confusionMatrix[$trueLabel] = ['TP' => 0, 'FP' => 0, 'FN' => 0];
            }

            if ($trueLabel == $predictedLabel) {
                $confusionMatrix[$trueLabel]['TP'] += 1;  // True Positive
            } else {
                $confusionMatrix[$trueLabel]['FN'] += 1;  // False Negative
                if (!isset($confusionMatrix[$predictedLabel])) {
                    $confusionMatrix[$predictedLabel] = ['TP' => 0, 'FP' => 0, 'FN' => 0];
                }
                $confusionMatrix[$predictedLabel]['FP'] += 1;  // False Positive
            }
        }

        return $confusionMatrix;
    }


    public function getKlasifikasiSVM()
    {

        $pagination = session('pagination', []);
        $pagedLabels = session('pagedLabels', []);
        $newPredictions = session('newPredictions', []);
        $accuracy = session('accuracy', []);
        $precision = session('precision', []);
        $recall = session('recall', []);
        $f1Score = session('f1Score', []);
        $predictions = session('predictions', []); // Ambil predictions dari session
        $confusionMatrix = session('confusionMatrix', []);

        return view('pages.analisis.hasil-klasifikasi', compact('accuracy', 'precision', 'recall', 'f1Score', 'predictions', 'pagination', 'pagedLabels', 'newPredictions', 'confusionMatrix'));
    }

    public function getWordCloud()
    {
        $DataLatih = session('hasil_prepocessing', []);
        if (empty($DataLatih)) {
            return response()->json([
                'error' => 'Data latih tidak ditemukan. Pastikan preprocessing data telah dilakukan sebelumnya.'
            ], 400);
        }

        $trainSamples = array_column($DataLatih, 'preprocessed_content');

        $wordFrequency = $this->getWordFrequency($trainSamples);

        // Format data untuk word cloud (kata dan frekuensi)
        $wordData = [];
        foreach ($wordFrequency as $word => $count) {
            $wordData[] = [$word, $count];
        }

        // Simpan data untuk ditampilkan di view
        return view('pages.analisis.wordcloud', compact('wordData'));
    }

    private function getWordFrequency($samples)
    {
        // $filePath = public_path('build/stopword/stopword.txt');
        $wordFrequency = [];
        $stopwords = $this->getStopWords(); // Function to fetch stopwords

        // Loop untuk menghitung frekuensi kata
        foreach ($samples as $text) {
            // Preprocessing: remove punctuation and convert to lowercase
            $text = strtolower(preg_replace('/[^a-z0-9\s]/', '', $text)); // Remove all non-alphanumeric characters
            $words = preg_split('/\s+/', $text); // Split words by one or more spaces

            foreach ($words as $word) {
                // Skip empty words and stopwords
                if (!empty($word) && !in_array($word, $stopwords)) {
                    // Count word frequency
                    $wordFrequency[$word] = isset($wordFrequency[$word]) ? $wordFrequency[$word] + 1 : 1;
                }
            }
        }

        return $wordFrequency;
    }


    // Function to get stopwords (you can customize this list or load it from a file)
    private function getStopWords()
    {
        return [
            'yang',
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
            'yaitu'
        ];
    }
}
