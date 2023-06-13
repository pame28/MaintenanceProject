<?php

namespace App\Models;

use App\Orchid\Filters\InventoryFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Maintenance extends Model
{
    use HasFactory, AsSource, Filterable;
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $guarded = []; //!Nunca pasar los métodos de $request->all() en las consultas de la base de datos, siempre pasar los campos que se necesitan.

    protected $allowedSorts = [
        'id',
        'maintenance_type_id',
        'user_id',
        'user_id_owner',
        'status',
        'created_at',
        'updated_at',
    ];

    protected $allowedFilters = [
        'id',
        'maintenance_type_id',
        'description',
        'user_id',
        'user_id_owner',
        'status',
        'created_at',
        'updated_at',
    ];
    public function maintenance_type()
    {
        return $this->belongsTo(Maintenance_type::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function user_owner()
    {
        return $this->belongsTo(User::class, 'user_id_owner')->withTrashed();
    }

    public function cpu()
    {
        return $this->belongsTo(Cpu::class)->withTrashed();
    }

    public function printer()
    {
        return $this->belongsTo(Printer::class)->withTrashed();
    }

}
