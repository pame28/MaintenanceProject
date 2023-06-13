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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('cpu_id')->nullable();
            $table->foreign('cpu_id')->references('id')->on('cpus');
            $table->unsignedBigInteger('printer_id')->nullable();
            $table->foreign('printer_id')->references('id')->on('printers');
            $table->unsignedBigInteger('department_id')->nullable();
            $table->foreign('department_id')->references('id')->on('departments');
            $table->date('expiration_date')->nullable();
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropForeign(['printer_id']);
            $table->dropForeign(['cpu_id']);
            $table->dropColumn('cpu_id');
            $table->dropColumn('printer_id');
            $table->dropColumn('department_id');
            $table->dropColumn('status');
            $table->dropSoftDeletes();
        });
    }
};
