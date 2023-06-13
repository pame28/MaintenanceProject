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
        Schema::create('cpus', function (Blueprint $table) {
            $table->id();
            $table->string('serial_number');
            $table->string('inventory_number');
            $table->unsignedBigInteger('model_id');
            $table->foreign('model_id')->references('id')->on('equipment_models');
            $table->string('storage_capacity', 20);
            $table->string('ram', 20);
            $table->enum('cpu_status', ['Disponible', 'Asignado', 'En mantenimiento', 'Obsoleto'])->default('Disponible');
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
        Schema::dropIfExists('cpus');
    }
};
