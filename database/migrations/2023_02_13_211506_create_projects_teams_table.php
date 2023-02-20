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
Schema::create('projects_teams', function (Blueprint $table) {
$table->id('team_id');
$table->foreignId('project_id')->constrained('client_projects')->onDelete('cascade');
$table->foreignId('manager_id')->constrained('managers')->onDelete('cascade');
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
        Schema::dropIfExists('projects_teams');
    }
};
