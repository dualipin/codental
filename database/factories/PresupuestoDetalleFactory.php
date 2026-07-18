<?php

namespace Database\Factories;

use App\Models\Presupuesto;
use App\Models\PresupuestoDetalle;
use App\Models\TratamientoCatalogo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PresupuestoDetalle>
 */
class PresupuestoDetalleFactory extends Factory
{
    protected $model = PresupuestoDetalle::class;

    public function definition(): array
    {
        $precio = fake()->randomFloat(2, 200, 5000);

        return [
            'presupuesto_id' => Presupuesto::factory(),
            'tratamiento_catalogo_id' => TratamientoCatalogo::factory(),
            'referencia_odontograma_id' => (string) fake()->optional(0.6)->numberBetween(1, 32),
            'precio_congelado' => $precio,
            'monto_descuento' => fake()->optional(0.3)->randomFloat(2, 0, $precio * 0.3) ?? 0,
            'justificacion_descuento' => fn (array $attrs) => $attrs['monto_descuento'] > 0
                ? fake()->randomElement(['Descuento por pronto pago', 'Promoción del mes', 'Paciente recurrente'])
                : null,
            'estado_tratamiento' => fake()->randomElement(['pendiente', 'en_progreso', 'completado']),
        ];
    }
}
