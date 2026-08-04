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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('android_app')->nullable();
            $table->string('apple_app')->nullable();
            $table->string('title');
            $table->text('text');
            $table->string('android_version');
            $table->string('ios_version');
            $table->boolean('force_update_android')->default(false);
            $table->boolean('force_update_ios')->default(false);

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
        Schema::dropIfExists('settings');
    }
};
