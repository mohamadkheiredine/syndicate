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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('full_name')->nullable();
            $table->string('name_code')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('country_code')->nullable();
            $table->unsignedBigInteger('mobile_number')->unique();
            $table->integer('pin')->nullable();
            $table->string('verificationID', 32)->nullable();
            $table->boolean('new_user')->default(true);
            $table->date('dob')->nullable();
            $table->integer('age')->nullable();
            $table->string('password')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('country_code');
            $table->index('verificationID');
            $table->index('new_user');
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
        Schema::dropIfExists('users');
    }
};
