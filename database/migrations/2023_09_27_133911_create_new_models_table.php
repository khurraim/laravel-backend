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
        Schema::create('new_models', function (Blueprint $table) {

            $table->id();
            $table->string('title');
            $table->string('location');
            $table->string('subLocation');
            
            $table->text('modelDescription');
            $table->bigInteger('age');
            $table->text('weight');

            $table->text('height');
            $table->text('phone_no');

            $table->string('nationality');
            $table->string('dressSize');
            $table->bigInteger('price');
            $table->text('featuredImage');
            $table->text('video');
            
            $table->text('created_by');

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
        Schema::dropIfExists('new_models');
    }
};
