<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photos', function (Blueprint $table) {
            $table->id(); // PK
            $table->bigInteger('shoot_id')->unsigned(); // Shoots FK
            $table->string('caption');
            $table->string('asset');
            $table->boolean('show_on_home')->default(0);
            $table->timestamps(); // Laravel timestamps

            // Constraints
            $table->foreign('shoot_id')->references('id')->on('shoots');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('photos');
    }
}
