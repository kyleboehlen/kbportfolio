<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSoftwareProjectTechnologiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('software_project_technologies', function (Blueprint $table) {
            // PK
            $table->bigInteger('project_id')->unsigned();
            $table->bigInteger('technology_id')->unsigned();

            // Constraints
            $table->primary(['project_id', 'technology_id']);
            $table->foreign('project_id')->references('id')->on('software_projects');
            $table->foreign('technology_id')->references('id')->on('software_technologies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('software_project_technologies');
    }
}
