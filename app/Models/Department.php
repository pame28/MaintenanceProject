<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Department extends Model
{
    use HasFactory, AsSource, Filterable;

    protected $guarded = []; //!Nunca pasar los métodos de $request->all() en las consultas de la base de datos, siempre pasar los campos que se necesitan.

    protected $allowedSorts = [
        'id',
        'department_name',
        'description',
    ];

    protected $allowedFilters = [
        'id',
        'department_name',
    ];

    public function user()
    {
        return $this->hasMany(User::class)->withTrashed();
    }
}
