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
        Schema::create('submitted_tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('student_name');
            $table->string('role');
            $table->string('task_name');
            $table->string('level');
            $table->string('points');
            $table->string('category');
            $table->string('answer');
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
        Schema::dropIfExists('submitted_tasks');
    }
};
