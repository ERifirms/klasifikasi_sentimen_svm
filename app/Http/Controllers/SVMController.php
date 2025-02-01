<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Phpml\Classification\SVC; // Menggunakan SVC, bukan SVM
use Phpml\SupportVectorMachine\Kernel;

use Phpml\Dataset\ArrayDataset;
use Phpml\Metric\Accuracy;
use Phpml\FeatureExtraction\TokenCountVectorizer;
use Phpml\Tokenization\WhitespaceTokenizer;
use Phpml\Preprocessing\Normalizer;

class SVMController extends Controller
{
    //
    public function klasifikasiSVM()
    {
        // Contoh data training dan label
        $samples = [
            [1, 2],
            [2, 3],
            [3, 3],
            [6, 5],
            [7, 8],
            [8, 8]
        ];
        $labels = ['A', 'A', 'A', 'B', 'B', 'B'];

        // Normalisasi data
        $normalizer = new Normalizer();
        $normalizer->fit($samples);
        $normalizer->transform($samples);

        // Split data menjadi 80% training dan 20% testing
        $trainSize = (int)(0.8 * count($samples));
        $trainSamples = array_slice($samples, 0, $trainSize);
        $trainLabels = array_slice($labels, 0, $trainSize);
        $testSamples = array_slice($samples, $trainSize);
        $testLabels = array_slice($labels, $trainSize);

        // Buat model SVM menggunakan kernel RBF
        $classifier = new SVC(Kernel::RBF, $C = 1.0, $gamma = 0.5);
        $classifier->train($trainSamples, $trainLabels);

        // Prediksi data testing
        $predictions = $classifier->predict($testSamples);

        // Hitung akurasi
        $accuracy = Accuracy::score($testLabels, $predictions) * 100;

        // Tampilkan hasil
        return response()->json([
            'predictions' => $predictions,
            'testLabels' => $testLabels,
            'accuracy' => $accuracy
        ]);
    }
}
