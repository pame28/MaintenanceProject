<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('printers', function (Blueprint $table) {
            $table->id();
            $table->string('serial_number', 20);
            $table->string('inventory_number', 20);
            $table->unsignedBigInteger('model_id');
            $table->foreign('model_id')->references('id')->on('equipment_models');
            $table->string('cartridge', 20);
            $table->string('connection_type');
            $table->enum('printer_status', ['Disponible', 'Asignado', 'En mantenimiento', 'Obsoleto'])->default('Disponible');
            $table->date('date_of_purchase')->nullable();
            $table->date('last_revised_date')->nullable();
            $table->integer('last_revised_user_id')->nullable();
            $table->text('observations')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('printers');
    }
};
