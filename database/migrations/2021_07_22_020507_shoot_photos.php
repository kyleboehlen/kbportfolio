<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ShootPhotos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shoot_photos', function (Blueprint $table) {
            // PK
            $table->bigInteger('shoot_id')->unsigned();
            $table->bigInteger('photo_id')->unsigned();

            // Constraints
            $table->primary(['shoot_id', 'photo_id']);
            $table->foreign('shoot_id')->references('id')->on('shoots');
            $table->foreign('photo_id')->references('id')->on('photos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shoot_photos');
    }
}
