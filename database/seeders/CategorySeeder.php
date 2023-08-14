<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->data();
    }

    private function data()
    {

        $categoryData = [
            'Olahraga',
            'Politik',
            'Gaya Hidup',
            'Otomotif',
            'Kesehatan'
        ];

        foreach($categoryData as $ct) {
            Category::create([
                'title' => $ct,
                'description' => 'deskripsi ' . $ct
            ]);
        }
    }
}
