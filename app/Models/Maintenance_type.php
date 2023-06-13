<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Maintenance_type extends Model
{
    use HasFactory, AsSource, Filterable;

    protected $guarded = []; //!Nunca pasar los métodos de $request->all() en las consultas de la base de datos, siempre pasar los campos que se necesitan.

    protected $allowedSorts = [
        'id',
        'type',
        'description',
    ];

    protected $allowedFilters = [
        'id',
        'type',
        'description'
    ];

    /* public function maintenances(){
        return $this->hasMany(Maintenance::class);
    } */
}
