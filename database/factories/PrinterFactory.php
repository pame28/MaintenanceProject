<?php

namespace Database\Factories;

use App\Models\Equipment_model;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Printer>
 */
class PrinterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'serial_number' => $this->faker->unique()->regexify('[A-Za-z0-9]{10}'),
            'inventory_number' => $this->faker->unique()->regexify('[A-Za-z0-9]{10}'),
            'model_id' => Equipment_model::factory()->create()->id,
            'cartridge' => $this->faker->word(),
            'connection_type' => $this->faker->randomElement(['USB', 'Red', 'USB y Red']),
            'printer_status' => $this->faker->randomElement(['Disponible', 'Asignado', 'En mantenimiento', 'Obsoleto']),
            'date_of_purchase' => $this->faker->date(),
            'last_revised_date' => null,
            'last_revised_user_id' => null,
            'observations' => $this->faker->paragraph,
        ];
    }
}
