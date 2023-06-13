<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Printer extends Model
{
    use HasFactory, AsSource, Filterable;

    protected $guarded = []; //!Nunca pasar los métodos de $request->all() en las consultas de la base de datos, siempre pasar los campos que se necesitan.

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $allowedSorts = [
        'id',
        'inventory_number',
        'model',
        'name',
        'brand_name',
        'department_name',
        'serial_number',
        'connection_type',
        'printer_status',
        'last_revised_date',
        'date_of_purchase',
        'last_revised_user_id'
    ];

    protected $allowedFilters = [
        'id',
        'inventory_number',
        'model',
        'name',
        'brand_name',
        'department_name',
        'serial_number',
        'connection_type',
        'printer_status',
        'last_revised_date',
        'date_of_purchase',
        'last_revised_user_id',
    ];

    public function model()
    {
        return $this->belongsTo(Equipment_model::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }


    public function maintenance()
    {
        return $this->hasMany(Maintenance::class)->withTrashed();
    }

    public function userOwner()
    {
        return $this->belongsTo(User::class, 'id', 'printer_id')->withTrashed();
    }
}
