<?php

declare(strict_types=1);


namespace App\Orchid\Filters;

use App\Models\Cpu;
use App\Models\Printer;
use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Select;

class InventoryFilter extends Filter
{
    /**
     * The displayable name of the filter.
     *
     * @return string
     */
    public function name(): string
    {
        if($this->request->get('typeEquipment') && !$this->request->get('inventory_number'))
            return 'Tipo de Dispositivo';
        else if ($this->request->get('inventory_number') && !$this->request->get('typeEquipment'))
            return 'Numero inventario';
        else
            return 'Dispositivo';
    }

    /**
     * The array of matched parameters.
     *
     * @return array|null
     */
    public function parameters(): ?array
    {
        return ['inventory_number', 'typeEquipment'];
    }

    /**
     * Apply to a given Eloquent query builder.
     *
     * @param Builder $builder
     *
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {
        $modelEquipment = $this->request->get('typeEquipment') == 'CPU' ? 'cpu' : 'printer';
        if($this->request->get('typeEquipment') && !$this->request->get('inventory_number')){
            return $builder->whereHas($modelEquipment, function (Builder $query) {
                $modelEquipment = $this->request->get('typeEquipment') == 'CPU' ? 'cpu' : 'printer';
                $query->where($modelEquipment.'_id', '!=', null)
                    ->orderBy('last_revised_date', 'desc');
            });
        }
        else {
            return $builder->whereHas('cpu', function (Builder $query) {
                $query->where('inventory_number', $this->request->get('inventory_number'));
            })->orWhereHas('printer', function (Builder $query) {
                $query->where('inventory_number', $this->request->get('inventory_number'));
            });
        }

    }

    /**
     * Get the display fields.
     *
     * @return Field[]
     */
    public function display(): iterable
    {
        return [

            Select::make('typeEquipment')
                ->options([
                    'CPU' => 'CPU',
                    'Impresora' => 'Impresora',
                ])
                ->value($this->request->get('typeEquipment'))
                ->empty('Todos')
                ->title('Tipo de dispositivo'),

            Select::make('inventory_number')
                ->fromModel(Printer::class, 'inventory_number', 'inventory_number')
                ->value($this->request->get('inventory_number'))
                ->empty('Todos')
                ->title('Numero inventario'),


        ];
    }
}
