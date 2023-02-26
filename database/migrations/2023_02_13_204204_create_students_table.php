<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules\Unique;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id('student_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('username')->Unique();
            $table->string('email')->Unique();
            $table->string('avater')->nullable();
            $table->string('role')->nullable();
            $table->unsignedBigInteger('team_id')->nullable();
            $table->foreign('team_id')->references('team_id')->on('teams');
            $table->string('password');
            $table->string('Authorization')->Unique()->nullable();
            $table->timestamps();
            $table->timestamp('last_login_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students');
    }
};
