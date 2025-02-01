<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataUjiSeeder extends Seeder
{
    public function run()
    {
        $dataUji = [
            ['content' => 'gemini sangat kreatif dalam banyak hal.', 'sentimen' => 'positif'],
            ['content' => 'terkadang Gemini mudah berubah pikiran.', 'sentimen' => 'netral'],
            ['content' => 'gemini memiliki kepribadian yang menarik dan menyenangkan.', 'sentimen' => 'positif'],
            ['content' => 'seringkali Gemini cenderung bingung dan tidak konsisten.', 'sentimen' => 'negatif'],
            ['content' => 'gemini selalu bisa membuat suasana menjadi lebih hidup.', 'sentimen' => 'positif'],
            ['content' => 'gemini tidak mudah ditebak, mereka sering berubah-ubah.', 'sentimen' => 'netral'],
            ['content' => 'gemini sangat pandai berbicara dan menarik perhatian.', 'sentimen' => 'positif'],
            ['content' => 'kadang-kadang Gemini terlalu banyak bicara dan kurang mendengarkan.', 'sentimen' => 'negatif'],
            ['content' => 'gemini adalah orang yang penuh ide dan inovatif.', 'sentimen' => 'positif'],
            ['content' => 'gemini sering kebingungan dalam membuat keputusan.', 'sentimen' => 'negatif'],
            ['content' => 'gemini menyukai tantangan dan selalu mencari hal baru.', 'sentimen' => 'positif'],
            ['content' => 'gemini bisa menjadi tidak sabar dan mudah frustrasi.', 'sentimen' => 'negatif'],
            ['content' => 'gemini sering berubah suasana hati, kadang sangat ceria dan kadang sangat murung.', 'sentimen' => 'netral'],
            ['content' => 'gemini memiliki banyak teman dan dikenal sebagai orang yang ramah.', 'sentimen' => 'positif'],
            ['content' => 'kadang-kadang, Gemini terlihat tidak serius dan suka main-main.', 'sentimen' => 'netral'],
        ];

        DB::table('data_uji')->insert($dataUji);
    }
}
