<?php

namespace App\Orchid\Layouts\User;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class UserPrinterLayout extends Rows
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
            Select::make("user.printer_id")
                ->fromModel(\App\Models\Printer::whereNotIn('id', \App\Models\User::whereNotNull('printer_id')->pluck('printer_id'))
                ->orWhere('id', $this->query['user']->printer_id), 'inventory_number')
                ->title("Impresora ")
                ->empty("No Impresora")
                ->help("Specify which Printer this account should belong to"),
        ];
    }
}
