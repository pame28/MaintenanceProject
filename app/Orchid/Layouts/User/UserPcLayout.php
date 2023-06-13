<?php

namespace App\Orchid\Layouts\User;

use App\Models\Cpu;
use App\Models\User;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class UserPcLayout extends Rows
{
    /**
     * Used to create the title of a group of form elements.
     *
     * @var string|null
     */
    protected $title;

    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     */
    protected function fields(): iterable
    {
        return [
            Select::make("user.cpu_id")
                ->fromModel(Cpu::whereNotIn('id', User::whereNotNull('cpu_id')->pluck('cpu_id'))
                ->orWhere('id', $this->query['user']->cpu_id), 'inventory_number')
                ->title("PC")
                ->empty("No PC")
                ->help("Specify which PC this account should belong to"),
        ];
    }
}
