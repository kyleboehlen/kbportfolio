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
        Schema::create('discord_bots', function (Blueprint $table) {
            $table->id(); // PK
            $table->string('name');
            $table->string('img')->nullable();
            $table->text('desc');
            $table->string('client_id');
            $table->string('permissions')->nullable();
            $table->string('scope');
            $table->timestamps(); // Laravel timestamps
            $table->softDeletes(); // Laravel soft deletes
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('discord_bots');
    }
};
