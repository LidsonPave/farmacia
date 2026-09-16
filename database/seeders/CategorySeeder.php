<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Analgésicos', 'description' => 'Medicamentos para alívio da dor e febre.'],
            ['name' => 'Antibióticos', 'description' => 'Medicamentos para tratamento de infeções bacterianas.'],
            ['name' => 'Antialérgicos', 'description' => 'Medicamentos para tratamento de alergias.'],
            ['name' => 'Vitaminas e Suplementos', 'description' => 'Suplementos vitamínicos e minerais.'],
            ['name' => 'Gastrointestinal', 'description' => 'Medicamentos para problemas digestivos e gástricos.'],
            ['name' => 'Respiratório', 'description' => 'Medicamentos para tratamento de problemas respiratórios.'],
            ['name' => 'Cardiovascular', 'description' => 'Medicamentos para tratamento de problemas cardíacos e de tensão arterial.'],
            ['name' => 'Dermatológico', 'description' => 'Medicamentos e produtos para uso na pele.'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
