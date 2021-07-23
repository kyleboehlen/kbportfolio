<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShootsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shoots', function (Blueprint $table) {
            $table->id(); // PK
            $table->string('name');
            $table->date('shot_on')->nullable();
            $table->text('desc')->nullable();
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
        Schema::dropIfExists('shoots');
    }
}
