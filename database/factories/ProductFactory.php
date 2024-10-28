<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
        ];
    }

    // public function definition(): array
    // {
    //     $title = fake()->unique()->name;  // Generate a unique title using Faker
    //     $slug = Str::slug($title);  // Create a slug from the title
    //     $description = fake()->paragraph;  // Generate a random description using Faker
    //     $subCategories = [44, 45];  // Sub-category IDs
    //     $subCatRandKey = $subCategories[array_rand($subCategories)];  // Randomly select a sub-category ID
    //     $brands = [19, 20];  // Brand IDs
    //     $brandKeys = $brands[array_rand($brands)];  // Randomly select a brand ID
    
    //     return [
    //         'title' => $title,
    //         'slug' => $slug,
    //         'description' => $description,  // Add the generated description
    //         'category_id' => 13,  // Static category ID
    //         'sub_category_id' => $subCatRandKey,  // Randomly selected sub-category ID
    //         'brand_id' => $brandKeys,  // Randomly selected brand ID
    //         'price' => fake()->randomFloat(2, 100, 1000),  // Generate a price between 100 and 1000 with 2 decimal places
    //         'compare_price' => fake()->randomFloat(2, 100, 1000),  // Generate a compare price
    //         'sku' => fake()->unique()->numerify('SKU###'),  // Generate a unique SKU with the format SKU123
    //         'barcode' => fake()->optional()->numerify('BARCODE####'),  // Optional barcode (nullable)
    //         'track_qty' => 'Yes',  // Default track_qty value
    //         'qty' => 10,  // Default quantity
    //         'is_featured' => 'Yes',  // Default value for is_featured
    //         'status' => 1,  // Default status value
    //     ];
    // }
}
