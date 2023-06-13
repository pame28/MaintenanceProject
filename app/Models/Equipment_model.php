<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Equipment_model extends Model
{
    use HasFactory, AsSource, Filterable;

    protected $guarded = []; //!Nunca pasar los métodos de $request->all() en las consultas de la base de datos, siempre pasar los campos que se necesitan.

    protected $allowedSorts = [
        'id',
        'model',
        'brand_name',
    ];

    protected $allowedFilters = [
        'id',
        'model',
        'brand_name'
    ];

    public function brand(){
        return $this->belongsTo(Brand::class);
    }

    public function brandName(){
        return $this->brand->brand_name;
    }

    public function printer(){
        return $this->hasOne(Printer::class)->withTrashed();
    }

    public function cpu(){
        return $this->hasOne(Cpu::class)->withTrashed();
    }
}

