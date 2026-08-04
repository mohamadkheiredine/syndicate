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
        Schema::create('social_media', function (Blueprint $table) {
            $table->id();
            $table->string('icon');
            $table->string('title');
            $table->string('link');
            $table->integer('pos')->nullable();
            $table->boolean('publish')->default(false);
            $table->timestamps();

            // Indexes
            $table->index('publish');
            $table->index('pos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('social_media');
    }
};
