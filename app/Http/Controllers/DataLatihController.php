<?php

namespace App\Http\Controllers;

use App\Models\DataLatih;
use Illuminate\Http\Request;
use Phpml\FeatureExtraction\TfIdfTransformer;
use Phpml\Tokenization\WhitespaceTokenizer;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class DataLatihController extends Controller
{
    //
    public function index()
    {
        $dataLatih = DataLatih::orderBy('id', 'desc')->paginate(10);

        return view('pages.data.latih', compact('dataLatih'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        // return response()->json(['request' => $request->all()]);
        // dd($request);
        $file = $request->file('file');
        $data = Excel::toArray([], $file)[0]; // Mengambil sheet pertama
        foreach ($data as $row) {
            if (isset($row[0], $row[1])) { // Asumsi kolom 0 = content, kolom 1 = sentimen
                $sentimen = strtolower(trim($row[1])); // Menghilangkan spasi dan memastikan lowercase

                // Validasi nilai sentimen
                if (in_array($sentimen, ['positif', 'negatif', 'netral'])) {
                    DataLatih::create([
                        'content' => $row[0],
                        'sentimen' => $sentimen
                    ]);
                } else {
                    // Abaikan baris dengan sentimen tidak valid
                    continue;
                }
            }
        }

        return redirect()->back()->with('success', 'File berhasil diunggah dan data disimpan.');
    }

    // Fungsi untuk menampilkan form tambah data latih
    public function create()
    {
        return view('data_latih.create');
    }

    // Fungsi untuk menyimpan data latih baru
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'content' => 'required|string',
            'sentimen' => 'required|in:positif,negatif,netral',
        ]);

        // Simpan data ke database
        DataLatih::create([
            'content' => $request->content,
            'sentimen' => $request->sentimen,
        ]);

        // Redirect dengan pesan sukses
        return redirect()->route('pages.data.latih')->with('success', 'Data latih berhasil ditambahkan.');
    }

    // Fungsi untuk menampilkan form edit data latih
    public function edit($id)
    {
        $data_update = DataLatih::findOrFail($id); // Mencari data berdasarkan ID
        return view('pages.data.latih', compact('data_update')); // Mengirim data ke view
    }

    // Fungsi untuk memperbarui data latih
    public function update(Request $request)
    {
        $data = DataLatih::find($request->id);
        $data->content = $request->content;
        $data->sentimen = $request->sentiment;
        $data->save();

        return redirect()->route('pages.data.latih')->with('success', 'Data successfully updated');
    }


    // Fungsi untuk menghapus data latih
    public function destroy($id)
    {
        $dataLatih = DataLatih::findOrFail($id);
        $dataLatih->delete();

        return redirect()->route('pages.data.latih');
    }
    // public function reulsttfidf()
    // {
    //     // Ambil data latih dari database
    //     $dataLatih = DataLatih::all();

    //     // Preprocessing data latih
    //     $preprocessedLatih = $dataLatih->map(function ($item) {
    //         $item->content = $this->preprocessText($item->content);
    //         return $item;
    //     });

    //     // Ambil dokumen dari data latih yang sudah dipreprocessing
    //     $documents = $preprocessedLatih->pluck('preprocessed_content')->toArray();

    //     // Hapus nilai null atau kosong dari array $documents
    //     $documents = array_filter($documents, function ($item) {
    //         return !is_null($item) && $item !== '';
    //     });

    //     // Debugging untuk memastikan dokumen yang terfilter
    //     Log::debug('Documents after filtering null or empty:', $documents);

    //     // Tokenisasi
    //     $tokenizer = new WhitespaceTokenizer();
    //     $tokenizedData = array_map(function ($text) use ($tokenizer) {
    //         // Cek apakah teks kosong sebelum tokenisasi
    //         if (empty($text)) {
    //             Log::warning('Dokumen kosong ditemukan, melewati tokenisasi.');
    //             return [];  // Kembalikan array kosong jika teks kosong
    //         }
    //         return $tokenizer->tokenize($text);
    //     }, $documents);

    //     // Debugging untuk memastikan tokenisasi
    //     Log::debug('Tokenized Data:', $tokenizedData);

    //     // Hitung TF-IDF
    //     try {
    //         $tfidfTransformer = new TfIdfTransformer();
    //         $tfidfTransformer->fit($tokenizedData);
    //         $tfidfScores = $tfidfTransformer->transform($tokenizedData);
    //     } catch (\Exception $e) {
    //         Log::error('Error dalam perhitungan TF-IDF: ' . $e->getMessage());
    //         return redirect()->back()->with('error', 'Gagal menghitung TF-IDF.');
    //     }

    //     // Kirim data ke view, termasuk data latih dan skor TF-IDF
    //     return view('pages.analisis.tf-idf', compact('tfidfScores'));
    // }
}
