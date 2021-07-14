<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSoftwareProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('software_projects', function (Blueprint $table) {
            $table->id(); // PK
            $table->string('name');
            $table->enum('type', config('software.enum.type'));
            $table->string('logo');
            $table->text('desc');
            $table->string('codebase_link')->nullable();
            $table->string('app_link');
            $table->timestamps(); // Laravel timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('software_projects');
    }
}
