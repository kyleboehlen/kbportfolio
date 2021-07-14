<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSoftwareTechnologiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('software_technologies', function (Blueprint $table) {
            $table->id(); // PK
            $table->string('name');
            $table->enum('bg_color', config('software.enum.bg_color'));
            $table->boolean('text_dark')->default($value = false);
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
        Schema::dropIfExists('software_technologies');
    }
}
