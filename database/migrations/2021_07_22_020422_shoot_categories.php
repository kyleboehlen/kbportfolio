<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ShootCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shoot_categories', function (Blueprint $table) {
            // PK
            $table->bigInteger('shoot_id')->unsigned();
            $table->tinyInteger('category_id')->unsigned();

            // Constraints
            $table->primary(['shoot_id', 'category_id']);
            $table->foreign('shoot_id')->references('id')->on('shoots');
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
        Schema::dropIfExists('shoot_categories');
    }
}
