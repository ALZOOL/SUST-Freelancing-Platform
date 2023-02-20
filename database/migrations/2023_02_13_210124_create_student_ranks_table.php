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
        Schema::create('student_ranks', function (Blueprint $table) {
          
            $table->unsignedBigInteger('student_id');
            $table->primary('student_id')->references('student_id')->on('students');
            $table->char('rank',3)->default('F');
            $table->integer('points')->default(0);;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_ranks');
    }
};
