<?php

namespace App\Orchid\Layouts\User;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class UserDepartmentLayout extends Rows
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
            Select::make("user.department_id")
                ->fromModel(\App\Models\Department::class , 'department_name')
                ->title("Departamento ")
                ->empty("No Departamento")
                ->help("Specify which Department this account should belong to"),
        ];
    }
}
