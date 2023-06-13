<?php

namespace App\Orchid\Layouts\Maintenance;

use App\Orchid\Filters\InventoryFilter;
use Orchid\Filters\Filter;
use Orchid\Screen\Layouts\Selection;

class InventoryFilterLayout extends Selection
{
    /**
     * @return Filter[]
     */
    public function filters(): iterable
    {
        return [
            InventoryFilter::class,
        ];
    }
}
