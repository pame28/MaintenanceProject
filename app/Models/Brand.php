<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Brand extends Model
{
    use HasFactory, AsSource, Filterable;

    protected $guarded = []; //!Nunca pasar los métodos de $request->all() en las consultas de la base de datos, siempre pasar los campos que se necesitan.

    protected $allowedSorts = [
        'id',
        'brand_name',
        'description',
    ];

    protected $allowedFilters = [
        'id',
        'brand_name',
    ];

    public function model(){
        return $this->hasMany(Equipment_model::class);
    }
}
