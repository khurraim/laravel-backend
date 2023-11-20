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
        Schema::create('footer_menus', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedBigInteger('page_id');  // Foreign Key
            $table->timestamps();


            // Define the foreign key relationship
            $table->foreign('page_id')->references('id')->on('pages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop the foreign key first
        Schema::table('footer_menus', function (Blueprint $table) {
            $table->dropForeign(['page_id']);
        });
        Schema::dropIfExists('footer_menus');
    }
};
