<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PhotoCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photo_categories', function (Blueprint $table) {
            // PK
            $table->bigInteger('photo_id')->unsigned();
            $table->tinyInteger('category_id')->unsigned();

            // Constraints
            $table->primary(['photo_id', 'category_id']);
            $table->foreign('photo_id')->references('id')->on('photos');
            $table->foreign('category_id')->references('id')->on('photography_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('photo_categories');
    }
}
