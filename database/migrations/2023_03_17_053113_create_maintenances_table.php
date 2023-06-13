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
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('maintenance_type_id');
            $table->foreign('maintenance_type_id')->references('id')->on('maintenance_types');
            $table->text('description');
            $table->text('solution')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('user_id_owner')->nullable();
            $table->unsignedBigInteger('printer_id')->nullable();
            $table->foreign('printer_id')->references('id')->on('printers');
            $table->unsignedBigInteger('cpu_id')->nullable();
            $table->foreign('cpu_id')->references('id')->on('cpus');
            $table->enum('status', ['Pendiente', 'En proceso', 'Finalizado'])->default('Pendiente');
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
        Schema::dropIfExists('maintenances');
    }
};
