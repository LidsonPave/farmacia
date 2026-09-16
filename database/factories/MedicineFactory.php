<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Medicine;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Medicine>
 */
class MedicineFactory extends Factory
{
    protected $model = Medicine::class;

    public function definition(): array
    {
        $salePrice = fake()->randomFloat(2, 30, 300);
        $purchasePrice = round($salePrice * (fake()->numberBetween(55, 75) / 100), 2);
        $minimumStock = fake()->numberBetween(10, 30);

        return [
            'category_id' => Category::inRandomOrder()->value('id'),
            'name' => fake()->unique()->words(2, true),
            'code' => 'MED' . str_pad((string) fake()->unique()->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT),
            'purchase_price' => $purchasePrice,
            'sale_price' => $salePrice,
            'stock_quantity' => $minimumStock + fake()->numberBetween(10, 50),
            'minimum_stock' => $minimumStock,
            'expiry_date' => now()->addDays(fake()->numberBetween(61, 730)),
            'status' => 'ativo',
        ];
    }

    public function lowStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock_quantity' => fake()->numberBetween(0, $attributes['minimum_stock']),
        ]);
    }

    public function nearExpiry(): static
    {
        return $this->state(fn () => [
            'expiry_date' => now()->addDays(fake()->numberBetween(1, 60)),
        ]);
    }

    public function expiredDate(): static
    {
        return $this->state(fn () => [
            'expiry_date' => now()->subDays(fake()->numberBetween(1, 180)),
        ]);
    }

    public function noExpiry(): static
    {
        return $this->state(fn () => [
            'expiry_date' => null,
        ]);
    }
}
