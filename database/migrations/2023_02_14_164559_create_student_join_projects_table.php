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
        Schema::create('student_join_projects', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id');
            $table->string('project_title');
            $table->integer('client_id');
            $table->string('client_email');
            $table->integer('student_id');
            $table->string('student_name');
            $table->string('student_role');
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
        Schema::dropIfExists('student_join_projects');
    }
};