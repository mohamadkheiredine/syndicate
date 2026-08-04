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
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('label');
            $table->string('country_code');
            $table->unsignedBigInteger('mobile_number');
            $table->string('city');
            $table->string('street');
            $table->string('building');
            $table->string('apartment');
            $table->string('instructions')->nullable();
            $table->decimal('lat', 10, 7);
            $table->decimal('lng', 10, 7);
            $table->string('google_map');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('user_id');
            $table->index('is_default');
            $table->index('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_addresses');
    }
};
