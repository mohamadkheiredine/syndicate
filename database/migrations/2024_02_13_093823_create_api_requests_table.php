<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApiRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_requests', function (Blueprint $table) {
            $table->id();
            $table->string('ip')->nullable();
            $table->string('x-forwarded-for')->nullable();
            $table->string('uuid')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('end_point')->nullable();
            $table->timestamps();

            // Add indexes
            $table->index('ip');
            $table->index('x-forwarded-for');
            $table->index('uuid');
            $table->index('mobile_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('api_requests');
    }
}
