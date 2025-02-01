<?php

namespace App\Http\Controllers;

use App\Models\DataUji;
use Illuminate\Http\Request;
use League\Csv\Reader;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use League\Csv\Statement;

class DataUjiController extends Controller
{
    //
    // Fungsi untuk menampilkan data latih
    public function index()
    {
        $dataUji = DataUji::orderBy('id', 'desc')->paginate(10);

        return view('pages.data.uji', compact('dataUji'));
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
                    DataUji::create([
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
        return view('data_uji.create');
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
        DataUji::create([
            'content' => $request->content,
            'sentimen' => $request->sentimen,
        ]);

        // Redirect dengan pesan sukses
        return redirect()->route('pages.data.uji')->with('success', 'Data latih berhasil ditambahkan.');
    }
    // Fungsi untuk menampilkan form edit data latih
    public function edit($id)
    {
        $data_update = DataUji::findOrFail($id); // Mencari data berdasarkan ID
        return view('pages.data.uji', compact('data_update')); // Mengirim data ke view
    }

    // Fungsi untuk memperbarui data latih
    public function update(Request $request)
    {
        $data = DataUji::find($request->id);
        $data->content = $request->content;
        $data->sentimen = $request->sentiment;
        $data->save();


        return redirect()->route('pages.data.uji')->with('success', 'Data successfully updated');
    }

    // Fungsi untuk menghapus data latih
    public function destroy($id)
    {
        $dataUji = DataUji::findOrFail($id);
        $dataUji->delete();

        return redirect()->route('pages.data.uji');
    }
}
