<?php

namespace App\Http\Controllers;

use App\Models\DataLatih;
use App\Models\DataUji;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function resultData()
    {
        $latihSample = DataLatih::all();
        $testSample = DataUji::all();

        $jumlahLatih = $latihSample->count();
        $jumlahUji = $testSample->count();

        return view('dashboard', compact('jumlahLatih', 'jumlahUji'));
    }
}
