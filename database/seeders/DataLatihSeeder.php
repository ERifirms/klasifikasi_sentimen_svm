<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataLatihSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['content' => 'Gemini dikenal sebagai pribadi yang cerdas dan kreatif.', 'sentimen' => 'positif'],
            ['content' => 'Gemini kadang terlihat tidak konsisten dengan pendapatnya.', 'sentimen' => 'negatif'],
            ['content' => 'Sebagai zodiak udara, Gemini sangat komunikatif dan suka bersosialisasi.', 'sentimen' => 'positif'],
            ['content' => 'Gemini sering dianggap sebagai pribadi yang sulit dimengerti.', 'sentimen' => 'negatif'],
            ['content' => 'Sifat Gemini yang ceria dan penuh energi membuat mereka disukai banyak orang.', 'sentimen' => 'positif'],
            ['content' => 'Gemini terkadang cenderung kurang fokus pada satu hal.', 'sentimen' => 'negatif'],
            ['content' => 'Gemini memiliki kemampuan untuk melihat berbagai sudut pandang dalam suatu masalah.', 'sentimen' => 'positif'],
            ['content' => 'Gemini sering kali berubah-ubah dalam mengambil keputusan penting.', 'sentimen' => 'negatif'],
            ['content' => 'Kecerdasan dan kepandaiannya berbicara membuat Gemini mudah menguasai banyak hal.', 'sentimen' => 'positif'],
            ['content' => 'Beberapa orang merasa Gemini terlalu banyak bicara tanpa memikirkan akibatnya.', 'sentimen' => 'negatif'],
            ['content' => 'Gemini sangat tertarik pada pengetahuan baru dan terus belajar sepanjang hidup.', 'sentimen' => 'positif'],
            ['content' => 'Gemini bisa terkesan berlebihan dengan rasa ingin tahunya yang tinggi.', 'sentimen' => 'netral'],
            ['content' => 'Gemini sering kali menginspirasi orang di sekitarnya dengan ide-ide baru mereka.', 'sentimen' => 'positif'],
            ['content' => 'Meskipun pandai, Gemini kadang cenderung ragu-ragu dalam mengambil langkah besar.', 'sentimen' => 'negatif'],
            ['content' => 'Sebagai teman, Gemini bisa sangat menyenangkan dengan suasana hati yang mudah berubah.', 'sentimen' => 'positif'],
            ['content' => 'Gemini sering dianggap tidak dapat diandalkan karena ketidakkonsistenan mereka.', 'sentimen' => 'negatif'],
            ['content' => 'Gemini suka tantangan dan mencari pengalaman baru dalam hidup mereka.', 'sentimen' => 'positif'],
            ['content' => 'Kadang-kadang Gemini terlihat seperti tidak pernah puas dengan apa yang mereka miliki.', 'sentimen' => 'negatif'],
            ['content' => 'Dengan daya tarik alami mereka, Gemini selalu bisa menarik perhatian banyak orang.', 'sentimen' => 'positif'],
            ['content' => 'Gemini sering merasa tertekan oleh ekspektasi yang tinggi dari orang lain.', 'sentimen' => 'negatif'],
        ];

        foreach ($data as $row) {
            DB::table('data_latih')->insert([
                'content' => $row['content'],
                'sentimen' => $row['sentimen'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
