<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AbsenPulang extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('absen_pulang', function (Blueprint $table) {
            $table->string('id',40);
            $table->string('absen',40);
            $table->timestamps();

            $table->primary('id');
            $table->foreign('absen')->references('id')->on('absen')->onDelete('restrict');
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('absen_pulang', function (Blueprint $table) {
            //
        });
    }
}
