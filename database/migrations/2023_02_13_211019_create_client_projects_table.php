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
        Schema::create('client_projects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')->references('client_id')->on('clients');
            $table->string('title');
            $table->string('category');
            $table->string('description');
            $table->string('deadline');
            $table->string('status')->nullable();
            $table->string('rank');
            $table->unsignedBigInteger('team_id')->nullable();
            $table->foreign('team_id')->references('id')->on('projects_teams')->nullable();
            $table->integer('frontend')->nullable();
            $table->integer('backend')->nullable();
            $table->integer('ui_ux')->nullable();
            $table->integer('security')->nullable();
            $table->integer('team_count');
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
        Schema::dropIfExists('client_projects');
    }
};
