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
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('phone')->nullable();
            $table->string('code');
            $table->string('name');
            $table->string('currency');
            $table->boolean('publish')->default(true);
            $table->timestamps();

            // Indexes
            $table->index('code');
            $table->index('publish');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('countries');
    }
};
