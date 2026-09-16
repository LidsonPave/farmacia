<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Medicine;
use Illuminate\Database\Seeder;

class MedicineSeeder extends Seeder
{
    public function run(): void
    {
        $medicinesByCategory = [
            'Analgésicos' => ['Paracetamol 500mg', 'Ibuprofeno 400mg', 'Aspirina 500mg', 'Diclofenac 50mg', 'Tramadol 50mg', 'Naproxeno 250mg'],
            'Antibióticos' => ['Amoxicilina 500mg', 'Azitromicina 500mg', 'Ciprofloxacina 500mg', 'Metronidazol 400mg', 'Doxiciclina 100mg', 'Cefalexina 500mg'],
            'Antialérgicos' => ['Cetirizina 10mg', 'Loratadina 10mg', 'Clorfenamina 4mg', 'Difenidramina 25mg', 'Fexofenadina 120mg', 'Prometazina 25mg'],
            'Vitaminas e Suplementos' => ['Vitamina C 500mg', 'Complexo B', 'Vitamina D3 1000UI', 'Ácido Fólico 5mg', 'Sulfato Ferroso 200mg', 'Multivitamínico'],
            'Gastrointestinal' => ['Omeprazol 20mg', 'Ranitidina 150mg', 'Metoclopramida 10mg', 'Loperamida 2mg', 'Hidróxido de Alumínio', 'Domperidona 10mg', 'Sais de Reidratação Oral'],
            'Respiratório' => ['Salbutamol 100mcg', 'Ambroxol 30mg', 'Dextrometorfano 15mg', 'Bromexina 8mg', 'Loratadina Xarope', 'Budesonida Inalador'],
            'Cardiovascular' => ['Losartana 50mg', 'Captopril 25mg', 'Atenolol 50mg', 'Amlodipina 5mg', 'Hidroclorotiazida 25mg', 'Sinvastatina 20mg', 'Enalapril 10mg'],
            'Dermatológico' => ['Hidrocortisona Creme', 'Clotrimazol Creme', 'Betametasona Creme', 'Ácido Fusídico Creme', 'Óxido de Zinco Pomada', 'Permetrina Loção'],
        ];

        $priceRanges = [
            'Analgésicos' => [20, 100],
            'Antibióticos' => [50, 250],
            'Antialérgicos' => [30, 120],
            'Vitaminas e Suplementos' => [40, 180],
            'Gastrointestinal' => [40, 200],
            'Respiratório' => [60, 250],
            'Cardiovascular' => [80, 350],
            'Dermatológico' => [50, 180],
        ];

        // 35 stock normal + 15 stock baixo
        $stockStates = array_merge(array_fill(0, 35, 'normal'), array_fill(0, 15, 'baixo'));
        shuffle($stockStates);

        // 30 validade normal + 10 proxima + 7 expirado + 3 sem validade
        $expiryStates = array_merge(
            array_fill(0, 30, 'normal'),
            array_fill(0, 10, 'proxima'),
            array_fill(0, 7, 'expirado'),
            array_fill(0, 3, 'sem_validade')
        );
        shuffle($expiryStates);

        $index = 0;

        foreach ($medicinesByCategory as $categoryName => $names) {
            $category = Category::where('name', $categoryName)->first();
            [$min, $max] = $priceRanges[$categoryName];

            foreach ($names as $name) {
                $salePrice = round(mt_rand($min * 100, $max * 100) / 100, 2);
                $purchasePrice = round($salePrice * (mt_rand(55, 75) / 100), 2);

                $minimumStock = mt_rand(10, 30);
                $stockQuantity = $stockStates[$index] === 'baixo'
                    ? mt_rand(0, $minimumStock)
                    : $minimumStock + mt_rand(10, 50);

                $expiryDate = match ($expiryStates[$index]) {
                    'proxima' => now()->addDays(mt_rand(1, 60)),
                    'expirado' => now()->subDays(mt_rand(1, 180)),
                    'sem_validade' => null,
                    default => now()->addDays(mt_rand(61, 730)),
                };

                Medicine::create([
                    'category_id' => $category->id,
                    'name' => $name,
                    'code' => 'MED' . str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT),
                    'purchase_price' => $purchasePrice,
                    'sale_price' => $salePrice,
                    'stock_quantity' => $stockQuantity,
                    'minimum_stock' => $minimumStock,
                    'expiry_date' => $expiryDate,
                    'status' => 'ativo',
                ]);

                $index++;
            }
        }
    }
}
